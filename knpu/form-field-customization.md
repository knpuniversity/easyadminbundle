# Form Field Customization

We can pretty much do anything to the list page. But we have *totally* ignored
the two most important views: edit and new. Basically, we've ignored all the forms!

Ok, the `edit` and `new` views have *some* of the same configuration as `list`
and `search`... like `title` and `help`. They also both have a `fields` key...
but it's quite different than `fields` under `edit` and `new`.

Start simple: for `Genus`, I want to control which fields are shown on the form:
I don't want to show *every* field. But instead of adding an `edit`
or `new` key, add `form`, `fields` below that, and the fields we want: `id`, `name`,
`speciesCount`, `funFact`, `isPublished`, `firstDiscoveredAt`, `subFamily` and
`genusScientists`.

Before we talk about this, try it! Yes! Behind the scenes, EasyAdminBundle uses
Symfony's form system... it just creates a normal form object by using our config.
Right now, it's adding these 8 fields with no special config. Then, the form system
is *guessing* which field *types* to use.

That's great... but why did we put `fields` under `form`, instead of `edit` or
`new`? Where the heck did `form` come from? First, there is *not* a `form` view.
But, since `edit` and `new` are *so* similar, EasyAdminBundle allows us to configure
a "fake" view called `form`. Any config under `form` will automatically be used for
`new` and `edit`. Then, you can keep customizing. Under `new` and `fields`, we can
*remove* the id field with `-id`.

And under `edit`, to include the `slug` field - which is *not* under `form`, just
add `slug`.

Ok, refresh the edit page. Yep! *Now* we have a `slug` field... but it's all the
way at the bottom. This is because the fields from `form` are added first, and *then*
any `edit` fields are added. We'll fix the order later.

And the `new` view does *not* have `id`.

## Customizing Field Types, Options

Go back into the EasyAdminBundle profiler. Under `new` and then `fields`, we can
see each field *and* its `fieldType`. That corresponds to the Symfony *form* type
that's being used for this field. Open up Symfony's form documentation and scroll
down to the built-in fields list.

Yes, we know these: `TextType`, `TextareaType`, `EntityType`, etc. When you use these
in a normal form class, you reference them by their full class name - like
`EntityType::class`. EasyAdminBundle re-uses these form types... but lets us use
a shorter string name... like just `entity`.

The most *important* way to customize a field is to change its type. For example,
see `funFact`? It's just a text field... but sometimes, fun facts are *so* fun...
a mere text box cannot contain them. No problem. Just like we did under `list`, we
can *expand* each field: set `property: funFact`, then `type: textarea`. You can
picture what this is doing internally: EasyAdminBundle now calls
`$builder->add('funFact', TextareaType::class)`.

It even works! From working with forms, we also know that `$builder->add()` has
a *third* argument: an options array. And yep, those are added here too. One normal
form option is called `disabled`. Let's use that on the `id` field. Change it to
use the expanded configuration - I'll even get fancy with multiple lines. Then, add
`type_options` set to an array with `disabled: true`.

Do the same thing below on the `slug` field. Oh, and EasyAdminBundle also has one
special config key called `help: Unique auto-generated value`.

Find your browser and go edit a genus. Yea... `id` is disabled... and so is `slug`.
*And*, we have a super cool help message below!

The cool thing about EasyAdminBundle is that if you're comfortable with the form
system... well... there's not much new to learn. You're simply customizing your
form fields in YAML instead of in PHP. 

For example, the `firstDiscoveredAt` field is a `DateType`. And that means, under
`type_options`, we could set `widget` to `single_text` to render that in one text
field. If your browser supports it, you'll see a cute calendar widget.
