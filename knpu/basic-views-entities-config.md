# Views & entities Config

With EasyAdminBundle, you can configure just about *everything*... in multiple different
ways. It's *great*! But also confusing. So, let's get it straight!

There are two different axis for configuring things. First, every entity has 5 different
"views": `list` - which is this one - `search`, `new`, `edit` and `show`. Each view
can be configured. Second, each *entity* can *also* be configured. And sometimes,
these overlap: you can tweak something for all list views, but then override that
list tweak for *one* specific entity.

## Global list Config

Let's see this in action! In `config.yml`, under `easy_admin`, you can configure the
list view by adding a `list` key. Set `title: 'List'`:

[[[ code('2c22584f11') ]]]

And yep! *Each* view can be
configured like this, by adding `search`, `show`, `edit` or `new`. Some config,
like `title`, will work under all of these. But mostly, each section has its own
config.

When you add `list` at this root level, it applies to *all* entities. Try it out:
yes! We just added a boring title to *all* the list pages!

For more context, add a magic wildcard: `%%entity_label%%`:

[[[ code('8ba883d645') ]]]

Try it! Much better!

There are just a *few* magic wildcards: `entity_label`, `entity_name` and `entity_id`.
The `entity_name` is the geeky, machine-name for your entity - like `GenusNote`.
The `entity_label` defaults to that same value... but we can change it to something
better.

## Configuring at the Entity Level

So far, we just have a simple list of all the entity admin sections we want. That's
great to get started... but not anymore! As *soon* as you need to configure an entity
further, you need to use an *expanded* format. Basically, instead of `- Genus`, use
`Genus` as the key and add a new line with `class` set to the full class name: `AppBundle\Entity\Genus`:

[[[ code('14d75230be') ]]]

Repeat this for everything else: `GenusNote`, `SubFamily` and `User`:

[[[ code('f9ee6ac5d8') ]]]

This didn't change anything... but now we are, oh yes, dangerous! Oh, *so* much can
be configured for each entity. Start simple: `label: Genuses`:

[[[ code('681ecd84d5') ]]]

Try that! Nice! The label is of course used in the title, but also in the navigation.

## Overriding the List view under an Entity

And here's where things get *really* interesting. The `list` config we added applies
to *all* entities. But we can also customize the `list` view for just *one* entity.
Under `GenusNote`, add a `label: 'Genus Notes'`:

[[[ code('d09c8cd546') ]]]

But more importantly, add `list`, then `title: 'List of Notes'`:

[[[ code('43c12dec1f') ]]]

Ok, check this out! The left navigation uses the new label. But the list page's
title is `List of notes`. 

Woohoo! To review: there are *5* views, and each view can be configured globally,
but also beneath each entity. If that makes sense, you're in *great* shape.

## The Search View

While we're here, go type a few letters into that search box. Yep! This is the `search`
view. It's almost identical to `list`: it re-uses its template and has almost identical
config keys.

## Overriding Entity View Config

In addition to `title`, one other key that *every* view has is `help`. First, set
this below the `Genus` section: "Genuses are not covered by warranty!":

[[[ code('d59dc11d78') ]]]

Notice, this is directly under the *entity* config, not under a specific view. Thanks
to this, it will *all* to *all* views for this entity. And... yep! It's at the top
of the search page, on list and on edit.

In the spirit of EasyAdminBundle, you can override this for each view below. For
`list`, set `help` to `Do not feed!`:

[[[ code('779c7863fc') ]]]

Nice!

And the `search` view still uses the default message. If you want to turn *off* the
help message here, you can totally do that. Under `search`, set `help` to `null`:

[[[ code('602ad0f8b8') ]]]

Refresh! Gone!

Ok, so we're not going to talk about *all* of the different keys available: it's
all pretty easy. The most important thing is to realize that each of the 5 views
can be configured on two different levels.

Next, let's talk about actions and the different ones that we can enable.
