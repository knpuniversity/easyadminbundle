# Actions Config

We now know that there are *5* views: `list`, `search`, `edit`, `new` and
~~banana~~ ... `show`. EasyAdminBundle also has an idea of "actions", which are basically
the *links* and buttons that show up on each view. For example, on `list`, we have
a `search` action, a `new` action and `edit` & `delete` actions. In the docs, they
have a section called [Actions Configuration](http://symfony.com/doc/current/bundles/EasyAdminBundle/book/actions-configuration.html)
that shows the "default" actions for each view. For example, from the "Edit" view,
you have a delete button and a list link... but you do *not* have a link to create
a new entity or eat a banana.

Whelp! These actions can be customized a lot: we can add some, take some away, tweak
their design and - gasp - create custom actions.

## Adding the show Action

In the docs, it says that the `list` view does *not* have the `show` action by default.
Yep, there's no little "show" link next to each item. *If* you want that, add it!
Under the global `list`, add `actions`, then `show`. 

We *only* need to say `show`, we don't need to re-list all the actions. That's because
the, `actions` configuration is a *merge*: it takes all of the original actions and
*adds* whatever we have here.

## Adding __toString Methods

So... how would we *remove* an action? We'll get there! But first, if you click,
"Show"... wow. Geez. Dang! The page is *very* broken:

> Object of class GenusNote could not be converted to string.

In a *lot* of places, EasyAdminBundle tries to convert your entity objects into
strings... ya know, so it can print them in a list or create a drop-down menu.
To get this working, we need to add `__toString()` methods to each entity. Let's do
that real quick!

In `Genus`, add `public function __toString()` and return `$this->name`. I'm casting
this into a string just to be safe - if it's null, you'll get an error. In `GenusNote`,
do the same thing: return `$this->getNote()`. In `GenusScientist`, we can return
`$this->getUser()`. That's an object... but we're about to add a `__toString()`
for it too! 

`SubFamily`, well hey! It already has a `__toString()`. I'll just add the string cast.

And finally, in `User` ... make this a bit fancier. Return `$this->getFullName()`
or `$this->getEmail()`, in case the user doesn't have a first or last name in the
database.

Try the show page again! Nice! It renders *all* of the properties, including
the *relations*, which is why it needed that `__toString()` method.

And because we have a `SubFamily` admin section, the `SubFamily` is a link that
takes us to its `show` view.

## Removing Actions

Speaking of `SubFamily`, as you can see... there's not much to it: just an id and
a name. And the "show" view, well, it's just the id and name again. Not too interesting.
In this case, I think it's overkill to have the `show` action for the `SubFamily`
entity. So let's kill it!

Back in `config.yml`, we just added the, `show` action to the `list` view globally.
Now, under `SubFamily`, we can *override* that `list` config. Add `actions` and -
to *remove* an action - use `-show`. Yep, use "minus" to take an action away.

Refresh! Ah, ha! The show link is gone.

## Disabling Actions

But... even though the link is gone, you can *totally* still get to the show page!
For example, if we click, "Edit", we can be annoying and change the `action` in the
URL to `show`. Genus!

Or... you can just go to the show page for any `Genus`: there is *still* a link to
the `SubFamily` show page.

That might be ok, but if you *truly* want to disable the show view, there's a special
config key for that. Under the entity itself, add `disabled_actions` set to an array
with `show` inside.

As *soon* as we do that ... the link vanishes in dramatic fashion! Let's be annoying
again: I'll go forward in my browser to get back to the show page URL. Refresh! Dang!
Now we get a huge error. The show view is *totally* gone.

## Customizing Action Design

There are two more things you can do with actions: custom actions - we'll talk about
those later - and making your actions pretty. That's probably even more important!

I want to tweak how the list actions look... but *only* for the `Genus` section.
Ok, find its config, go under `list` and add `actions`. This time, rather than adding
or removing actions, we want to *customize* them. So instead of using a simple string
like `show`, use an expanded configuration with `name: edit`. We are now proudly
configuring the `edit` action.

There are a few things we can do here, like `icon: pencil` and `label: Edit`. The
`icon` option - which shows up in a few places - allows you to add Font Awesome icons.
For the value, just use whatever comes *after* the `fa-`. So, to get the `fa-pencil`
icon, say `pencil`.

Make the `show` action just as fancy, with `icon: info-circle` and a *blank* label.
OoooOooo. Refresh to see how that looks!

Oh man, it's actually kind of ugly... I need to work on my styling skills. But it
totally works! 
