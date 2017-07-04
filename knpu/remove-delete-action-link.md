# Dynamically Remove the delete Action Link

Another chapter, another problem to solve! I need to hide the delete button on the
list page if an entity is published. So... nope! We can't just go into `config.yml`
and add `-delete`. We need to override the `list.html.twig` template and take control
of those actions manually.

Copy that file. Then, up inside our `views` directory, I want to show the *other*
way of overriding templates: by convention. Create a new `easy_admin` directory,
and paste the template there. And... that's it! EasyAdminBundle will automatically
know to use *our* list template.

## Dumping Variables

The *toughest* thing about overriding a template is... well... figuring out what
variables you can use! In `list.html.twig`... how about in the `content_header`
block, add `{{ dump() }}`. And in `_id.html.twig`, do the same: I want to
see what the variables look like in each template.

Ok, refresh the genus list page! Awesome! This first dump is from `list.html.twig`.
It has the same `fields` configuration we've been looking at in the profiler, a
`paginator` object and a few other things, including configuration for this specific
section.

The other dumps come from `_id.html.twig`. The big difference is that we're rendering
*one* `Genus` each time this template is called. So it has an `item` variable
set to the `Genus` object. That will be super handy. If some of the other keys
are tough to look at, remember, a lot of this already lives in the EasyAdminBundle
profiler area.

## Extending the Original Template

Ok, take out those dumps! So, how can we hide the delete button for published
genuses? It's actually a bit tricky.

In `list.html.twig`, if you search, there is a variable called `_list_item_actions`.
This contains information about the actions that should be rendered for each row.
It's used further below, in a block called `item_actions`. The template it renders -
`_actions.html.twig` - generates a link at the end of the row for each action.

Let's dump `_list_item_actions` to see *exactly* what it looks like.

Ah, ok! It's an array with 3 keys: `edit`, `show` and `delete`. We need to remove
that `delete` key, *only* if the entity is published. But how?

Here's my idea: if we override the `item_actions` block, we could *remove* the
`delete` key from the  `_list_item_actions` array and then call the parent `item_actions`
block. It would use the new, smaller `_list_item_actions`.

Start by deleting *everything* and extending the base layout: `@EasyAdmin/default/list.html.twig`...
so that we don't need to duplicate everything. Next, add `block item_actions` and
`endblock`. Twig isn't really meant for complex logic like removing keys from an
array. But, to accomplish our goal, we don't have any other choice. So, set
`_list_item_actions = _list_item_actions|filter_admin_actions(item)`. That filter
does *not* exist yet: we're about to create it.

Just to review, open up the original `list.html.twig`. The `_list_item_actions` variable
is set up here. Later, the `for` loop creates an `item` variable... which we have
access to in the `item_actions` block.

## Creating the Filter Twig Exension

Phew! All *we* need to do now is create that filter! In `src/AppBundle/Twig`, create
a new PHP class: `EasyAdminExtension`. To make this a Twig extension, extend
`\Twig_Extension`. Then, go to the `Code -> Generate` menu - or Command + N on a
Mac - and override the `getFilters()` method.

Here, return an array with the filter we need:
`new \Twig_SimpleFilter('filter_admin_actions', [$this, 'filterActions'])`. Down
below, create `public function filterActions()` with two arguments. First, it will
be passed an `$itemActions` array - that's the `_list_item_actions` variable. And
second, `$item`: whatever entity is being listed at that moment.

Ok, let's fill in the logic: `if $item instanceof Genus && $item->getIsPublished()`,
then `unset($itemActions['delete'])`. At the bottom, `return $itemActions`.

Phew! That should do it! This project uses the new Symfony 3.3 autowiring, auto-registration
and autoconfigure `services.yml` goodness. So... we don't need to configure anything:
`EasyAdminExtension` will automatically be registered as a service and tagged with
`twig.extension`. In other words... it should just work.

Let's go. Refresh... and hold your breath.

Haha, it *kind* of worked! Delete *is* gone... but so is everything else. And you
may have noticed why. We *did* change the `_list_item_actions` variable... but we
forgot to call the parent block. Add `{{ parent() }}`.

Try it again. Got it! The delete icon is only there when the item is *not* published.
This was a *tricky* example... which is why we did it! But usually, customizing things
is easier. Technically, the user could still go directly to the URL to delete the
`Genus`, but we'll see how to close that down later.
