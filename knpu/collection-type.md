# CollectionType Field

The form system does a pretty good job guessing the correct field types... but nobody
is perfect. For example, the `genusScientists` field is *not* setup correctly. Click
the clipboard icon to open the form profiler.

Yep, `genusScientist` is currently an `EntityType` with `multiple` set to true. Thanks
to EasyAdminBundle, it renders this as a cool, tag-like, auto-complete box. Fancy!

*But*... that's not going to work here: the `GenusScientist` entity has an extra
field called `yearsStudied`. When you link a `Genus` and a `User`, we need to allow
the admin to *also* fill in how many *years* the `User` has studied the `Genus`.

In the Symfony series, we did a *lot* of work to create a `CollectionType` field that
used `GenusScientistEmbeddedForm`. Thanks to that, in the admin, we just need to
update the form to look like this.

Change `genusScientists` to use the expanded syntax. From here, you can guess what's
next! Set `type: collection` and then add `type_options` with the 4 options you
see here: `entry_type: AppBundle\Form\GenusScientistEmbeddedForm`, `allow_delete: true`,
`allow_add: true`, `by_reference: false`.

Let's see what happens! Woh! Ignore how *ugly* it is for a minute. It *does* work!
We can remove items and add new ones.

But it looks weird. When we created this form for our custom admin area - we *hid*
the user field swhen editing... which looks really odd now. Open the `GenusScientistEmbeddedForm`.
We used a form event to accomplish this: if the `GenusScientist` had an id, we unset
the `user` field. Comment that out for now and refresh. Cool: this section *at least*
makes more sense now.

## The CollectionType Problems

But... there are *still* some problems! First, it's *ugly*! I know this is just an
admin area... but wow! If you want to use the `CollectionType`, you'll probably need
to create a custom form theme for this *one* field and render things in a more intelligent
way. We'll do something similar in a few minutes.

Second... this only works because we already did a lot of hard work setting up the
relationships to play well with the CollectionType. Honestly, the CollectionType
is both the best and worst form type: you can do some really complex stuff... but
it requires some seriously tough setup. You need to worry about the owning and the
inverse sides of the relationship, and things called `orphanRemoval` and cascading.
There is some significant Doctrine magic going on behind the scenes to get it working.s

So in a few minutes, we're going to look at a more custom alternative to using the
collection type.

## Virtual Form Field

But first, I want to show you one more thing. Go to the `User` section and edit a
User. We haven't touched *any* of this config yet. In `config.yml`, under `User`,
add `form` then `fields`. Let's include `email` and `isScientist`. 

Right now, the form has `firstName` and `lastName` fields... which makes sense: there
are `firstName` and `lastName` properties in `User`. But just like we did earlier
under the `list` view, instead of having `firstName` and `lastName`, we could actually
have, just `fullName`. And nope... there is *not* a `fullName` property. But as long
as we create a `setFullName()` method, we can *totally* add it to the form. Actually,
this isn't special to `EasyAdminBundle`, it's just how the form system works!

Now... this example is a little crazy. This code will take everything *before* the
first space as the first name, and everything after as the last name. Totally imperfect,
but you guys get the idea.

And now that we have `getFullName()` and `setFullName()`, add that as a field:
`property: fullName`, `type: text` and a help message.

Keep going to add `avatarUri` and `universityName`.

Try it out! Yes! It looks great... *and*... it even submits! Next up, let's add a
field that needs custom JavaScript to work.
