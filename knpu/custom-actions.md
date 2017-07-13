# Adding a Custom Action

We know there are a bunch of built-in *actions*, like "delete" and "edit. But sometimes
you need to manipulate an entity in a different way! Like, how could we add a "publish"
button next to each Genus?

There are... two different ways to do that. Click into the show view for a Genus.
On show, the actions show up at the bottom. Before we talk about publishing, I want
to add a new button down here called "feed"... ya know... because Genuses get hungry.
When we click that, it should send the user to a custom controller where we can
write whatever crazy code we want.

## Custom Route Actions

The first step should feel very natural. We already know how to add actions, remove
actions and customize how they look. Under `Genus`, add a new `show` key and `actions`.
Use the expanded configuration, with `name: genus_feed` and `type: route`.

There are two different custom action "types": `route` and `action`. Route is simple:
it creates a new link to the `genus_feed` route. And you can use any of the normal
action-configuring options, like `label`, `css_class: 'btn btn-info` or an `icon`.

## Adding the Route Action Endpoint

Next, we need to actually *create* that route and controller. In `src/AppBundle/Controller`,
open `GenusController`. At the top, add `feedAction()` with `@Route("/genus/feed")`
and `name="genus_feed` to match what we put in the config.

Notice the URL for this is just `/genus/feed`. It does not start with `/easyadmin`.
And so, it's not protected by our `access_control` security.

That should be enough to get started. Refresh! There's our link! Click it and...
good! Error! I love errors! Our action is still empty.

So here's the question: when we click feed on the `Genus` show page... the EasyAdminBundle
must *somehow* pass us the id of that genus... right? Yes! It does it via query
parameters... which are a bit ugly! So I'll open up my profiler and go to "Request / Response".
Here are the GET parameters. We have `entity` and `id`!

Now that we know that, this will be a pretty traditional controller. I'll type-hint
the `Request` object as an argument. Then, fetch the entity manager and the `$id`
via `$request->query->get('id')`. Use that to get the `$genus` object:
`$em->getRepository(Genus::class)->find($id)`.

Cool! To feed the `Genus`, we'll re-use a `feed()` method from a previous tutorial.
Start by creating a menu of delicious food: `shrimp`, `clambs`, `lobsters` and...
`dolphin`! Then choose a random food, add a flash message and call `$genus->feed()`.

Now that all this hard work is done, I want to redirect back to the show view for
this genus. Like normal, `return $this->redirectToRoute()`. And actually, EasyAdminBundle
only has *one* route... called `easyadmin`. We tell it where to go via query parameters,
like `action` set to `show`, `entity` set to `$request->query->get('entity')`...
or we could just say `Genus`, and `id` set to `$id`.

That is it! Refresh the show page! And feed the genus. Got it! We can hit that over
and over again. Hello custom action.

## Custom Controller Action

There's also *another* way of creating a custom action. It's a bit simpler and a
bit stranger... but has one advantage: it allows you to create different implementations
of the action for different entities.

Let's try it! In `config.yml`, add another action. This time, set the name to
`changePublishedStatus` with a `css_class` set to `btn`.

Let's do as *little* work as possible! So...refresh! We have a button! Click it!
Bah! Big error! But, it explains how the feature works:

> Warning call_user_func_array expects parameter 1 to be a valid callback, class
> AdminController does not have a method changePublishedStatusAction.

Eureka! All we need to do is create that method... then celebrate!

## Overriding the AdminController

To do that, we need to sub-class the core `AdminController`. Create a new directory
in `Controller` called `EasyAdmin`. Then inside, a new PHP class called `AdminController`.
To make this extend the normal `AdminController`, add a `use` statement for it:
use `AdminController as BaseAdminController`. Extend that: `BaseAdminController`.

Next, create that action method: `changePublishedStatusAction`. Notice the config
key is just `changePublishedStatus` - EasyAdminBundle automatically expects that
`Action` suffix.

And now that we're in a controller method... we're comfortable! I mean, we could
write a killer action in our sleep. But... there's a gotcha. This method is not,
exactly, like a traditional controller. That's because it's not called by Symfony's
routing system... it's called directly by EasyAdminBundle, which is trying to "fake"
things.

In practice, this means one important thing: we *cannot* add a `Request` argument.
Actually, *all* of the normal controller argument tricks will not work.. because
this isn't *really* a real controller.

## Fetching the Request & the Entity

Instead, the base `AdminController` has a few surprises for us: protected properties
with handy things like the entity manager, the request and some EasyAdmin configuration.

Let's use this! Get the id query parameter via `$this->request->query->get('id')`.
Then, fetch the object with `$entity = $this->em->getRepository(Genus::class)->find($id)`.

Now things are easier. Change the published status to whatever it is *not* currently.
Then, `$this->em->flush()`.

Set a fancy flash message that says whether the genus was just published or unpublished.
And finally, at the bottom, I want to redirect back to the show page. Let's go steal
that code from `GenusController`. The one difference of course is that `$request`
needs to be `$this->request`.

## Pointing to our AdminController Classs

Ok friends. Refresh! It works! Ahem... I mean, we totally get the exact same error!
What!?

This is because we haven't told Symfony to use *our* AdminController yet: it's still
using the one from the bundle. The fix is actually in `routing.yml`. This tells Symfony
to import the annotation routes from the bundle's `AdminController` class... which
means *that* class is used when we go to those routes. Change this to import routes
from `@AppBundle/Controller/EasyAdmin/AdminController.php` instead. It will *still*
read the same route annotations from the base class, because we're extending it. But
now, it will use *our* class when that route is matched.

That should be all we need. Try it. Boom! Genus published. Do it again! Genus
unpublished! The power... it's intoxicating...

Next! We're going to go rogue... and start adding our own custom hooks... like right
before or after an entity is inserted or updated.
