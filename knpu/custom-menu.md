# Customizing the Menu

What else can we do with the menu? Well, of course, we can *expand* the simple
entity items to get more control. To expand it, add `entity: User`. Now we can go
*crazy* with `label: Users`.

Remember, you can *also* specify the label under the `User` entity itself. If we
added a `label` key here, it would apply to the menu, the header to that section
and a few other places. But under `menu`, it *just* changes the menu text.

Do the same thing for `GenusNote`, with `label: Notes`, and also for sub families:
`entity: SubFamily, label: Sub-Families`.

At this point, it should be *no* surprise that we can control the *icon*
for each menu. Like, `icon: user`, `icon: sticky-note` and `icon: ''`.

Before configuring anything, each item has a little arrow icon. With empty quotes,
even that icon is gone.

## Adding Menu Separators & Sub-Menus

Oh, but the fanciness does, not, stop! The menu does a *lot* more than just simple
links: it has separators, groups and sub-links. Above `Genus`, create a new item
that *only* has a label. Yep, this has *no* `route` key and no `entity` key. We're
not linking to anything.

Instead, this just adds a nice separator. Or, you can go a step further and create
a sub-menu. Change this new menu item to use the expanded format. Then, add a `children`
key. Indent *all* the other links so that they live under this.

And just to make it even nicer, add a separator called `Related`.

Try it! Try it! Nice! The `Genus` menu expands to show the sub-items and the `Related`
separator.

## Custom Links!

We can also link to the different entity sections, but with different *sort* options.
We already have a `Genus` link that will take us to that page with the normal sort.
But let's not limit ourselves! We could also add another link to that *same* section,
with a different label: `Genuses (sorted by ID)` and a `params` key. Here, we can
control *whatever* query parameters we want, like `sortField: id`,  `sortDirection: ASC`
and... heck `pizza: delicious`. That last query parameter won't do anything... but
it doesn't make it any less true!

Ok, refresh! Then try out that new link. Yea! We're sorting by id and you might also
notice in the address bar that `pizza=delicious`.

On that note, one of the other query parameters is `action`, which we can also set
to anything. Copy this entire new menu link and - at the top of `children` - paste
it. This time, let's link to the show page of 1 *specific* genus... our favorite
"Pet genus". To do that, set `action` to `show` and `id` to some id in the database,
like 2.

This isn't anything special, we're just taking advantage of how the query parameters
work in EasyAdminBundle.

And while we're here, it might also be nice to add a link to the front-end of our
app. This is also nothing special: add a new link that points to the `app_genus_list`
route called "Open front-end". 

Refresh! And try that link. Nice!

## External Links

In addition to routes, if you want, you can just link to external URLs. Go to the
bottom of the list... and make sure we're at the root level. Add a new section called
"Important stuff"  with `icon: explanation` and a `children` key. I'll paste a couple
of *very* important external links for silly kittens and wet cats.

Yep, instead of `entity` or `route` keys, you can skip all of that and just add
`url`. And of course, you can set the `target` on any item.

## Re-organizing the Config

Ok team, our admin menu is complete! The *last* thing I want to show you isn't
anything special to this bundle: it's just a nice way to organize any configuration.
In fact, this trick will become the standard way to organize things in Symfony 4.

Right now, well, our admin configuration goes from line 81 of `config.yml` to line
258. Wow! It's *huge*!

To clear things up, I like to create a new file called `admin.yml`. Copy *all* of
this config, remove it, and add it to `admin.yml`. Perfect!

Now, we just need to make sure that Symfony loads this file. At the top of `config.yml`,
just load another resource: `admin.yml`.

And that is it! When we refresh, everything still works!

Phew, we're done! EasyAdminBundle is *great*. But of course, depending on *how* custom
you need things, you might end up overriding a *lot* of different parts. Many things
can be done via configuration. But by using the tools that we've talked about, you
can really override everything. Ultimately, customizing things will *still* be a
*lot* faster than building all of this on your own.

All right guys, thank you *so* much for joining me! And a *huge* thanks to my co-author
[Andrew](https://github.com/ifdattic) for doing all the *actual* hard work.

Ok, seeya next time!
