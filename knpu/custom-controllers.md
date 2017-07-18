# Override Controllers

When we submit a form, obviously, EasyAdminBundle is taking care of *everything*:
handling validation, saving and adding a flash message. That's the best thing ever!
Until... I need to hook into that process... then suddenly, it's the *worst* thing
ever! What if I need to do some custom processing right before an entity is created
or updated?

There are 2 main ways to hook into EasyAdminBundle...and I want you to know both.
Open the `User` entity. It has an `updatedAt` field. To set this, we could use Doctrine
lifecycle callbacks or a Doctrine event subscriber.

But, I want to see if we can set this instead, by hooking into EasyAdminBundle. In
other words, when the user submits the form for an update, we need to run some code.

## The protected AdminController Methods

The first way to do this is by adding some code to the controller. Check this
out: open up the base `AdminController` from the bundle and search for `protected function`.
Woh... There are a *ton* of methods that we can override, beyond just the actions.
Like, `createNewEntity()`, `perPersistEntity()` and `preUpdateEntity()`.

If we override `preUpdateEntity()` in *our* controller, that will be called right
before *any* entity is updated. There are a few other cool things that you can override
too.

## Per-Entity Override Methods

Ok, easy! Just add `preUpdateEntity()` to our `AdminController`, right? Yep... but
we can do better! If we override `preUpdateEntity()`, it will be called whenever
*any* entity is updated. But we really *only* want it to be called for the `User`
entity.

Once again, EasyAdminBundle has our back. Inside the base controller, search for
`preUpdate`. Check this out: right before saving, it calls some `executeDynamicMethod`
function and passes it `preUpdate`, a weird `<EntityName>` string, then `Entity`.

Actually, the bundle does this type of thing all over the place. Like above, when
it calls `createEditForm()`. Whenever you see this, it means that bundle will *first*
look for an entity-specific version of the method - like `preUpdateUserEntity()` -
and call it. If that doesn't exist, it will call the normal `preUpdateEntity()`.

This is *huge*: it means that each entity class can have its *own* set of hook
methods in our `AdminController`!

## One Controller per Entity

And now that I've told you that... we're going to do something completely different.
Instead of having one controller - `AdminController` - full of entity-specific hook
methods like `preUpdateUserEntity` or `createGenusEditForm` - I prefer to create
a custom controller class for each entity.

Try this: in the `EasyAdmin` directory, copy `AdminController` and rename it to
`UserController`. Then, remove the function. Use the Code -> Generate menu - or
Command + N on a mac - to override the `preUpdateEntity()` method. And don't forget
to update your class name to `UserController`.

We're going to configure things so that this `UserController` is used *only* for
the `User` admin section. And that means we can safely assume that the `$entity` argument
will *always* be a `User` object.

And that makes life easy: `$entity->setUpdatedAt(new \DateTime())`.

But how does `EasyAdminBundle` know to use this controller *only* for the `User` entity?
That happens in `config.yml`. Down at the bottom, under `User`, add
`controller: AppBundle\Controller\EasyAdmin\UserController`.

And *just* like that! We have one controller that's used for *just* our `User`.

Try it out! Let's go find a user... how about id 20. Right now, its `updateAt` is
null. Edit it... make some changes... and save! Go back to show and... we got it!

## Organizing into a Base AdminContorller

This little trick unlocks a lot of hook points. But if you look at `AdminController`,
it's a little messy. Because, `changePublishedStatusAction()` is *only* meant to
be used for the `Genus` class. But *technically*, this controller is being used
by *all* entities, except `User`.

So let's copy `AdminController` and make a new `GenusController`! Empty `AdminController`
completely. Then, make sure you rename the new controller class to `GenusController`.

But before we set this up in config, change the extends to `extends AdminController`,
and remove the now-unused `use` statement. Repeat that in `UserController`. Yep,
now *all* of our sections share a common base `AdminController` class. And even
though it's empty now, this could be *really* handy later if we ever need to add
a hook that affects *everything*.

Love it! `UserController` has only the stuff it needs, `GenusController` holds
only things that relate to `Genus`, and if we need to override something for all
entities, we can do that inside `AdminController`.

Don't forget to go back to your config to tell the bundle about the `GenusController`.
All the way on top, set the `Genus` controller to `AppBundle\Controller\EasyAdmin\GenusController`.
Now we're setup to do some really, really cool stuff.
