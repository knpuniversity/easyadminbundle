# Event Hooks

There's one other *major* way to hook into things with EasyAdminBundle... and it's
my favorite! Go back to the base `AdminController` and search for "event". You'll
see a *lot* in here! Whenever EasyAdminBundle does, well, pretty much anything...
it dispatches an event: `PRE_UPDATE`, `POST_UPDATE`, `POST_EDIT`, `PRE_SHOW`, `POST_SHOW`...
yes we get the idea already!

And this means that we can use standard Symfony event subscribers to totally kick
EasyAdminBundle's butt!

## Creating an Event Subscriber

Create a new `Event` directory... though, this could live anywhere. Then, how about,
`EasyAdminSubscriber`. Event subscribers always implement `EventSubscriberInterface`.
I'll go to the Code->Generate menu - or command+N on a Mac - and choose
"Implement Methods" to add the one required method: `getSubscribedEvents()`.

EasyAdminBundle dispatches a *lot* of events... but fortunately, they all live as
constants on a helper class called `EasyAdminEvents`. We want to use `PRE_UPDATE`.
Set that to execute a new method `onPreUpdate` that we will create in a minute.

But first, I'll hold command and click into that class. Dude, this is cool: this
puts *all* of the possible hook points right in front of us. There are a few different
categories: most events are either for customizing the actions and views or for
hooking into the entity saving process.

That difference is important, because our subscriber method will be passed *slightly*
different information based on which event it's listening to.

Back in our subscriber, we need to create `onPreUpdate()`. That's easy, but it's
Friday and I'm *so* lazy. So I'll hit the alt+enter shortcut and choose "Create Method".
Thank you PhpStorm Symfony plugin!

Notice that it added a `GenericEvent` argument. In EasyAdminBundle, *every* event
passes you this same object... just with different data. So, you kind of need to
dump it to see what you have access to.

Since we're using Symfony 3.3 and the new service configuration, my event subscriber
will automatically be loaded as a service and tagged as an event subscriber. If that
just blew your mind, check out our Symfony 3.3 series!

This means we can just... try it! Edit a user and submit. Bam!

## Fetching Info off the Event

For this event, the important thing is that we have a `subject` property on `GenericEvent`...
which holds the `User` object. We can get this via `$event->getSubject()`.

Remember though, this `PRE_UPDATE` event will be fired for *every* entity - not just
`User`. So, we need to check for that: if `$entity instanceof User`, then we know
it's safe to work our magic.

Since we already took care of setting the `updatedAt` in the controller, let's do
something different. The `User` class *also* has a `lastUpdatedBy` field, which should
be a `User` object. Let's set that here.

That means we need to get the currently-logged-in `User` object. To get that from
inside a service, we need to use another service. At the top, add a constructor.
Then, type-hint the first argument with `TokenStorageInterface`. Watch out: there are
two of them... and oof, it's impossible to know which is which. Choose either of
them for now. Then, name the argument and hit alt+enter to create and set a new
property.

Back on top... this is not the right `use` statement. I'll re-add `TokenStorageInterface`:
make sure you choose the one from `Security\Core\Authentication`.

In our method, fetch the user with `$user = $this->tokenStorage->getToken()->getUser()`.
And if the `User` is *not* an `instanceof` our `User` class, that means the user
isn't actually logged in. In that case, set `$user = null`.

Then, `$entity->setLastUpdatedBy($user)`.

Woohoo! Thanks to the new auto-wiring stuff in Symfony 3.3, we don't need to configure
*anything* in `services.yml`. Yep, with some help from the type-hint, Symfony already
knows what to pass to our `$tokenStorage` argument.

So go back, refresh and... no errors! It's always creepy when things work on the
first try. Go to the show page for the User id 20. Last updated by is set!

Next, we're going to hook into the bundle further and learn how to completely disable
actions based on security permissions.
