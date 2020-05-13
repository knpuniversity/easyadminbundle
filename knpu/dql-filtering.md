# DQL Filtering & Sorting

What else could we *possibly* configure with the list view? How about sorting, or
*filtering* list via DQL. OoooOOooo.

## Configuring Sort

First, sorting... which we get for free. Already, the genuses are sorted by id,
but we can click any column to sort by that. But this isn't sticky: when you
come back to the genus list page, it's back to filtering by id.

Sorting by *name* would be a bit more awesome. And you can probably *guess* what
the config looks like to do this. Under `Genus` and `list`, add `sort: name`:

[[[ code('e6bbe64fdc') ]]]

This is the *new* default field for sorting.

## Sorting via Relations

Oh, but we can get fancier. Under `GenusNote`, what if I told you I wanted to sort
by the name of the `Genus` it's related to? Yea, that would mean sorting *across*
a relation. But that's *totally* possible: `sort: ['genus.name', 'ASC']`:

[[[ code('91d2e1d101') ]]]

This also controls the direction. It sorts descending by default.

Try it! Nice! This works... just don't get *too* confident and try to do this across
*multiple* relationships... that's not going to work.

## Disabling Sort Fields

The ability to sort via *any* field with no setup is great! Though... sometimes
it doesn't make sense - like with the "User avatar" field. To tighten things up,
you can disable sorting. Find that field's `list` config and add a new option at
the end: `sortable: false`:

[[[ code('4df25c8c11') ]]]

And... gone!

## DQL Filtering

Ok, let's turn to something fun: DQL filtering. Like, what if we want to *hide*
some genuses entirely from the list and search page?

But first, so far, it *seems* like we're limited to one entity section per entity.
That's a lie! Let me show you: add a new section under `entities` called
`GenusHorde` - I just made that up. Below, set its class to `AppBundle\Entity\Genus`:

[[[ code('0ad5d75da0') ]]]

You see, some scientists are worried that certain genuses are becoming too
large... and threaten the survival of mankind. They want a new `GenusHorde` section
where they can keep track of all of the genuses that have a lot of species. It's
scary stuff, so we'll add a label: `HORDE of Genuses` with a scary icon:

[[[ code('723e0404e4') ]]]

***TIP
Fun fact! You can press `Control`+`Command`+`Space` to open up the icon menu on a Mac.
***

And all of a sudden... ah! We have a new "Horde of Genuses" section! Run!!!

Of course, this still shows *all* genuses. I want to filter this to *only*
list genuses that have a *lot* of species. Start by adding a `list` key and a new,
awe-inspiring option: `dql_filter`. For the value, pretend that you're building a
query in Doctrine. So, `entity.speciedCount >= 50000`:

[[[ code('86c9a03ba0') ]]]

The alias will *always* be `entity`.

Try it! Ten down to... only 7 menacing genuses!

And just like any query, you can get more complex. How about: `AND entity.isPublished = true`:

[[[ code('2fa5782012') ]]]

And to *really* focus on the genuses that are certain to overtake humanity, sort
it by `speciesCount` and give the section a helpful message: `Run for your life!!!`
Add scary icons for emphasis:

[[[ code('47f1d38cf2') ]]]

Ok... refresh! Ah... now only *three* genuses are threatening mankind.

Oh, and search automatically re-uses the `dql_filter` from list: these are 2 results
from the possible 3. And like always, you can override this. Under `search`, set the
`dql_filter` to the same value, but without the `isPublished` check:

[[[ code('b4e0c5c65a') ]]]

Try that. Boom! 3 more genuses that - when published - will spell certain doom for
all.

Next! We'll save humanity by learning how to override the many templates that
EasyAdminBundle uses.
