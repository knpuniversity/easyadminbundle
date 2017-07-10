# More about List Field Types

Navigate to the `User` entity section... open up the EasyAdminBundle profiler...
and check out the list fields.

I want to talk a bit more about these field "data types". Check out `isScientist`.
Its data type is set to `toggle`, my favorite type! Go back to the main page of the
documentation and open [List, Search and Show Views Configuration][list_search_show_configuration].

## List Field Types and Options

Down the page a little, it talks about how to "Customize the Properties Appearance".
This is *great* stuff. First, it lists the valid *options* for all the fields, like
`property`, `label`, `css_class`, `template` - which we'll talk about later - and yes!
The `type`, which controls that `dataType` config. There are a *bunch* of built-in types,
including all of the `Doctrine` field types and a few special fancy ones from EasyAdminBundle,
like *toggle*. The "toggle" type is actually *super* cool: it renders the field as
a little switch that turns the value on and off via AJAX.

## Changing to the boolean data type

Let's take control of the User fields. Below that entity, add `list`, then `fields`,
with `id` and `email`:

[[[ code('01dbdc4ae4') ]]]

Let's customize `isScientist` and set its label to `Is scientist?`. And as *cool*
as the toggle field is, for the great sake of learning, change it to `boolean`:

[[[ code('59a6dff2ac') ]]]

Then add, `firstName`, `lastName`, and `avatarUri`:

[[[ code('abe0c22822') ]]]

Try that! Ok! The `isScientist` field is now a "less-cool" Yes/No label. Open
up the `EasyAdminBundle` config to see the difference. Under list... fields... `isScientist`,
yep! `dataType` is now boolean... *and* it's using a different template to render it.
More on that later.

## Virtual Fields

Back in the config, obviously, these are all property names on the `User` entity.
But... that's not required. As long as there is a "getter" method, you can
invent new, "virtual" fields. Our `User` does *not* have a `fullName` property...
but it *does* have a `getFullName()` method. So, check this out: remove `firstName`
and `lastName` and replace it with `fullName`:

[[[ code('72a26c7aa9') ]]]

Try that out! Magic! 

## The email and url Fields

As we saw earlier, EasyAdminBundle also has a few *special* types. For example, expand
the `email` property and set its type to `email`:

[[[ code('7636c15044') ]]]

While we're here, do the same for `avatarUri`, setting it to `url`:

[[[ code('1342139095') ]]]

Try that! I know, it's not earth-shattering, but it is nice: the email is now a `mailto`
link and the `avatarUri` is a link to open in a new tab.

## The image Type

Of course, `avatarUri` is an image... so it would be *way* trendier to... ya know...
actually render an image! Yea! But let's do it somewhere else: go to the `GenusNote`
section. Then, in `config.yml`, under the entity's `list` key - add `fields`. Let's
show `id` and `username`:

[[[ code('cfcb4b5597') ]]]

One of the fields is called the `userAvatarFileName`, which is a simple text field
that stores an image filename, like `leanna.jpeg` or `ryan.jpeg`. I want that to
show up as an image thumbnail. To do that, add `property: userAvatarFilename`,
`label: User avatar` and... `type: image`:

[[[ code('d29f986276') ]]]

Before we try that, also add `createdAt` and `genus`:

[[[ code('30cb992f0d') ]]]

Actually, `genus` is the property that points to the related `Genus` *object*,
which is pretty cool:

[[[ code('07bac2d97e') ]]]

That will *totally* work because our `Genus` class has a `__toString()` method:

[[[ code('ce6fc2e9ae') ]]]

Refresh! Ok, it *kinda* works... there *is* an image tag! Yea... it's broken, but
let's try to be positive! Right click to open that in a new tab. Ah, it's look for
the image at `http://localhost:8000/leanna.jpeg`. In our simple system, those images
are actually stored in a `web/images` directory. In a more complex app, you might
store them in an `uploads/` directory or - even better - up on something like S3.
But no matter where you store your images, you'll need to configure this field to
point to the right path.

How? Via a special option on the `image` type: `base_path` set to `/images/`:

[[[ code('7f5ce80d58') ]]]

You can of course also use an absolute URL.

Try it! There it is! And it's even got a fancy lightbox. 

Next up, let's finish talking about the list view by taking crazy control of filtering
and ordering.


[list_search_show_configuration]: http://symfony.com/doc/current/bundles/EasyAdminBundle/book/list-search-show-configuration.html
