# Configuring the List Fields

We've been tweaking a bunch of stuff on the list view. But... what about this big
giant table in the middle!? How can we customize that?

Actually, EasyAdminBundle did a pretty good job with it: it guesses which fields
to show, humanizes the column header labels and renders things nicely. Good job Javier!

## The EasyAdminBundle Profiler

Before we tweak all of this, see that magic wand in the web debug toolbar? Say 
"Alohomora" and click to open that in a new tab. This is the `EasyAdminBundle` profiler...
and it's *awesome*. Here, under "Current entity configuration", we can see all of
the config we've been building for this entity, *including* default values that it's
guessing for us. This is a *sweet* map for knowing what can be changed and how.

Under `list`, then `fields`, it shows details about all the columns used for the table.
For example, under `name`, you can see `type => string`. Actually, `dataType` is
the really important one.

Here's the deal: each field that's rendered in the table has a different *data type*,
like `string`, `float`, `date`, `email` and a bunch others. EasyAdminBundle guesses
a type, and it affects how the data for that field is rendered. We can change the
type... and *anything* else you see here.

## Controller Fields

How? Under `Genus` and `list`, add `fields`. Now, list the exact fields that you want
to display, like `id`, `name` and `isPublished`. These 3 fields were already shown
before.

Let's also show `firstDiscoveredAt`... but! I want to tweak it a little. Just like
with actions, there is an "expanded" config format. Add `{ }` with `property: firstDiscoveredAt`.

Now... what configuration can we put here? Because this is a `date` field, it has
a `format` option. Set it to `M Y`. And, *all* fields have a `label` option. Use
"Discovered".

Keep going! Add `funFact` and then one more expanded property: `property: speciesCount`.
This is an `integer` type, which also has a `format` option. For fun, set it to
`%b` - binary format! Yea know because, scientists are nerds and like puzzles.

***TIP
The `format` option for number fields is passed to the `sprintf` function.
***

If your head is starting to spin with all of these types and options that I'm pulling
out of the air, don't worry! Almost *all* of the options - like `label` - are shared
across all the types. There are very few type-specific options like `format`.

And more importantly, in a few minutes, we'll look at a list of *all* of the valid
types and their options.

Ok! Close the profiler tab and refresh. Bam! The table has our 6 columns!

## Customizing the Search View

Try out the search again: look for "quo". Ok nice! Without any work, the search
view re-uses the `fields` config from list.

You *can* add a `fields` key under `search`, but it means something different. Add
`fields: [id, name]`. Out-of-the-box, the bundle searches *every* field for the
search string. You can see that in the queries. But now, it *only* searches `id`
and `name`.

Next, let's dive into some of the more interesting field types and their config.
