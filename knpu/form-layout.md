# Tweaking the Form Layout

We've talked a lot about customizing the forms... which mostly means using the config
to change the field types or adding a custom form theme to control how the individual
fields look.

But, what about the form *layout*? Like, what if I wanted to put the email & full
name fields in a section that floats to the left, and these other two fields in
a section that floats to the right?

Well... that's not really part of the form component: we usually do this by adding
markup to our template. But of course, the template lives inside EasyAdminBundle!

In turns out, there are two great ways to control your form's layout. First..., well,
you could just override the template and go *crazy*! For example, inside the bundle,
find `new.html.twig`. It has a block called `entity_form`. And, to render the form,
it just calls the `form()` function. This means that there's no form layout at *all*
by default: it just barfs out all the fields.

But... that's awesome! Because we could override this template, replace *just* the
`entity_form` block, and go bonkers by rendering the form *however* we need. And
since we can override each template on an entity-by-entity basis... well... suddenly
it's super easy to customize the exact form layout for each section.

## Form Layout Config Customizations

Phew! But... there is an *even* easier way that works for about 90% of the use-cases.
And that is... of course... with configuration. EasyAdminBundle comes with a bunch
of different ways to add dividers, sections and groups inside the form.

So, let's do it! Start with `User`. Let's reorganize things: put `fullName` on top,
then add a new type called `divider`. Put `avatarUri` after the divider, then another
divider, `email`, divider, `isScientist` and `universityName`:

[[[ code('b9c28c9373') ]]]

On a technical level... this is kind of geeky cool: `divider` is a *fake* form field!
I mean, in the bundle itself, it is *literally* a form field. And you can see a
few others, like `section` and `group`. We'll use all three.

Try out the page! Hello divider! It's nothing too fancy, but it's nice.

## Adding a Section

To go further, we can divide things into sections by using `type: section`. For
example, start here by saying `type: section, label: 'User Details'`:

[[[ code('8090d8ca92') ]]]

And then, inside, we'll have `fullName`, keep the `divider`, keep `avatarUri`, but replace
the next `divider` with the expanded syntax: `type: section, label: 'Contact Information'`.
And like many other places, you can add an `icon`, a `help` message and a `css_class`:

[[[ code('b888a39db6') ]]]

With `email` in its own section, change the last divider to `type: section, label: Education`:

[[[ code('1351ac8a6d') ]]]

Ok, let's see how this looks!

Not bad! Each field appears inside whatever section is above it.

## Reorganizing all Fields under form

The last organizational trick is the *group*, which gives you *mad* control over
the width and float of a bunch of fields.

To see an example, go up to the `Genus` form.

And first, remember how we organized most fields under the `form` key... but then
tweaked a few final things under `new` and `edit`? Well, when EasyAdminBundle reads
this, all of the fields under `form` are added first... and *then* any extra fields
under `new` or `edit` are added. That means, in `edit`, our `slug` field is printed
*last* on the form. And... there's not really a good way to control that. This gets
even a little bit more problematic when you want to organize fields into sections
or groups. How could you organize the `slug` field into the same section as `name`?
Right now, you can't!

For that reason, it's best to configure *all* of your fields under `form`. Then,
use `new` and `edit` *only* to *remove* fields you don't want. Copy the `slug`
field and remove `edit` entirely. Then, under `form`, paste this near the top:

[[[ code('63645974e9') ]]]

To keep `slug` off of the `new` form, just add `-slug`:

[[[ code('049957b14e') ]]]

The end result is the same, but with complete control over the field order.

## Adding Form Groups

Ok, back to adding *groups*. First, move `id` and `slug` to the end of the form.
Then, on top, add a new group: `type: group, css_class: 'col-sm-6', label: 'Basic Information'`:

[[[ code('ecf75522bb') ]]]

You can picture what this is doing: adding a div with `col-sm-6`, putting a header
inside of it, and then printing any fields below that, but in the div.

And that's huge! Because thanks to the `col-sm-6` CSS class, we can really start
organizing how things look.

Move `funFact` and `isPublished` a bit further down. Then, after `subFamily`, add
a `section` labeled `Optional`:

[[[ code('45637fc889') ]]]

Yep, you can totally mix-and-match groups and sections.

At this point, `funFact` and `isPublished` *will* still be in the group, but they'll
also be in a section within that group. And since `genusScientists` is pretty big,
let's put that in their own group with `css_class: col-sm-6` and `label: Studied by...`:

[[[ code('7e525b4265') ]]]

Finally, at the bottom, add one more group. I'll use the expanded format this
time: `css_class: col-sm-6` and `label: Identification`. And yep, groups can have
`icon` and `help` keys:

[[[ code('f3c45b70cf') ]]]

Phew! While I did this, I added some line breaks *just* so that this all looks a
bit more clear: here's one group, here's a second group, the last group is at the
bottom.

But what does it actually *look* like? Let's find out! Refresh!

Oh, this feels good. The "Basic Information" group is on the left with the "Optional
section" at the bottom. The other two groups float to the right.

Now, sometimes, you might want to force the "Identification" group to go onto its
own line. Basically, you want to add a CSS `clear` after the first two groups.

To do that, on the group, add a special CSS class, called `new-row`:

[[[ code('32b1a6dcdf') ]]]

And *now* it floats to the next line. So, groups are a really, really neat way
to control how things are rendered. It adds some nice markup, and we can add whatever
classes we need. So, there's not much you can't do.
