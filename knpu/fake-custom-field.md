# Form Theming For a Completely Custom Field

Let's look at one more way to make a *ridiculously* custom field. Right now, we're
using the `CollectionType`... which works... but is totally ugly. And the *only* reason
it works is that we did a lot of work in a previous tutorial to get the relationship
setup properly.

And even if you *can* get the `CollectionType` working, you may want to add more
bells and whistles to the interface. So here's the plan: we're not going to use
the `CollectionType`... at all. Instead, we'll write our own HTML and JavaScript
to create our *own* widget, which will use AJAX to delete and add new entries. Actually,
we won't do all of that right now - but I'll show you how to get things setup so
you can get back to writing that custom code.

## Configuring a Fake Field

Back in `config.yml`, find the `genusScientists` field, change its type to `text`
and delete the 4 options:

[[[ code('b0974ae0af') ]]]

Whaaaat? Won't this break like crazy!? The `genusScientists` field holds
a collection of `GenusScientist` objects... not just some text!

Totally! Except that we're going to add one magic config: `mapped: false`:

[[[ code('39e5ebf28e') ]]]

As *soon* as I do that, this is no longer a real field. I mean, when the form renders,
it will *not* call `getGenusScientists()`. And when we submit, it will *not* call
`setGenusScientists()`. In fact, you could even change the field name to something
totally fake... and it would work fine! This field *will* live in the form... but
it doesn't read or set data on your entity. It's simply a way for us to "sneak" a
fake field into our form.

Like we did in the last chapter, add a CSS class to the field: `js-scientists-field`:

[[[ code('259a84484c') ]]]

This time I'll use the standard `attr` option under `type_options`... but not for
any particular reason.

Let's go see what this looks like! Yep, it's just a normal, empty text field:
empty because it's not bound to our entity... at all - so it has no data.

## Form Theme for One Field

Here's the goal: I want to replace this text field with our own Twig code, where
we can build whatever crazy genus scientist-managing widget we want! How? The answer
is to work with the form system: create a custom form theme that *just* overrides
the widget for this *one* field.

To find out how, click the clipboard icon to get into the form profiler. Under
`genusScientists`, open up the view variables. See this one called `block_prefixes`?
This is the *key* for knowing the name of the block you should create in a form
theme to customize this field. For example, to customize the `widget` for this
field, we could create a block called `form_widget`, `text_widget` or `_genus_genusScientists_widget`.
The last block would *only* affect this one field.

Copy that name. Then, in `app/Resources/views/easy_admin`, create a new file called
`_form_theme.html.twig`. Add the block: `_genus_genusScientists_widget` with its
`endblock`:

[[[ code('296294ff32') ]]]

Are you feeling powerful yet? If not, you will soon. Before we start writing our
awesome code, we need to tell Symfony to use this form theme. In previous tutorials,
we learned how to add a custom form theme to our entire app... but in this case,
we really only need this inside of our easy admin area.

EasyAdminBundle gives us a way to do this. In `config.yml`, under `design`, add
`form_theme`. We're actually going to add two: `horizontal` and `easy_admin/_form_theme.html.twig`:

[[[ code('c27d7918c4') ]]]

EasyAdminBundle actually ships with two custom form themes: `horizontal` and `vertical`...
the difference is just whether the labels are next to, or above the fields. By
default, `horizontal` is used. When you add your own custom form theme, you need
to include `horizontal` or `vertical`... to keep using it.

Ok... let's kick the tires! Close the profiler and refresh. Ahhhhhhh!

> Unrecognized option "form_themes" under "easy_admin.design"

Ok, my bad. It's `form_theme`:

[[[ code('e913e1c4b7') ]]]

Thank you validation.

Now... we've got it! Our text shows up where the field should be. We can put anything
here: like some HTML or an empty div that JavaScript fills in. Heck, we could create
a React or Vue.js app and point it at this div. It's simple... but the possibilities
are endless.

## Rendering the Genus Scientists

Let's see a quick example to get the creative juices flowing! Let's create a
table that lists all of the genus scientists:

[[[ code('6286570822') ]]]

Inside a `tbody`, we're ready to loop over the scientists! But... uh... how can
I get them? What variables do I have access to right here?

Go back to the form profiler, find `genusScientists` and look again at the view variables.
These are all the variables that we have access to from within our form theme. But because
we set the field to `mapped` false... um... we actually don't have access to our
`Genus` object! That's  a problem. But! Because we're inside EasyAdminBundle, it
gives us a special `easyadmin` variable... with an `item` key equal to our `Genus`!
Phew!

Ok! In the table, loop: `for genusScientist in easyadmin.item.genusScientists`:

[[[ code('d4e7d70e05') ]]]

Add the `tr` and print out a few fields: `genusScientist.user` and `genusScientist.yearsStudied`:

[[[ code('40412eccb3') ]]]

Let's also add a fake delete link with a class and a `data-url` attribute. But leave
it blank:

[[[ code('d8d7ca884b') ]]]

In your app, you might create a delete AJAX endpoint and use the `path()` function
to put that URL here so you can read it in JavaScript.

Cool! To make this a *bit* more realistic, open `custom_backend.js`. Let's find those
`.js-delete-scientist` elements and, on click, call a function. Add the normal
`e.preventDefault()` and... an `alert('to do')`:

[[[ code('2a6d7eb950') ]]]

The rest, is homework!

Let's try it! There it us! A nice table with a delete icon. There's more work to do,
but you can totally do it! This is just normal coding: create a delete endpoint,
call it via JavaScript and celebrate!

With form stuff behind us, let's turn to adding custom *actions*, like, a publish
button.
