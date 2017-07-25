# CSV Export

When we started this tutorial, we kept getting the same question:

> Ryan, would you rather have rollerblades for feet or chopsticks for hands
> for the rest of your life?

I don't know the answer, but [Eddie from Southern Rail][eddie] can definitely
answer this.

We also got this question:

> How can I add a "CSV Export" button to my admin list pages?

And now we know *why* you asked this question: it's tricky! I *want* to add a button
on top that says "Export". But, when you add a custom action to the list page...
those create links *next* to each item, not on top. That's *not* what we want!

## Defining the Custom Action

So let's see if we can figure this out. First, in `config.yml`, under the global
`list`, we *are* going to add a new action, called `export`:

[[[ code('e92d45cf55') ]]]

Now, if we refresh... not surprisingly, this adds an "export" link next to *every*
item. And if we click it, it tries to execute a new `exportAction()` method.

So this is a bit weird: we do *not* want this new link on each row - we'll fix
that in a few minutes. But we *do* need the new `export` action. Why? Because as
*soon* as we add this, it's now *legal* to create a link that executes `exportAction()`.
And that means that we could manually add this link somewhere else... like on top
of the list page.

## Adding the Custom Link (Conditionally)

Open up our custom `list.html.twig`:

[[[ code('7392f61426') ]]]

I'll also hold `Command` and click to open the parent `list.html.twig` from the bundle.
If you scroll down a little bit, you'll find a block called `global_actions`. Ah,
it looks like it's rendering the search field. The `global_actions` block represents
this area on top.

In other words, if we want to add a new link here, `global_actions` is the place
to do it! Copy that block name and override it inside of our template: `global_actions`
and `endlock`:

[[[ code('4f384583fa') ]]]

Inside, we'll add the Export button.

But wait! I have an idea. What if we only want to add the export button to *some*
entities? Sure, I added the `export` action in the `global` section... but we could
still remove it from any other entity by saying `-export`. Basically, I want this
button to be smart: I only want to show it *if* the `export` action is enabled for
this entity.

How can we figure that out? In the parent template, you'll find a really cool if statement
that checks to see if an action is enabled. Steal it!

In our case, change `search` to `export`:

[[[ code('fbb02f946d') ]]]

At this point, we can do *whatever* we want. So, very simply, let's add a new link
that points to the `export` action. Add a `button-action` div for styling:

[[[ code('033accc7ad') ]]]

Then, inside, a link with `btn btn-primary` and an `href`. How can we point to the
`exportAction()`? Remember, the bundle only has one route: `easyadmin`. For the
parameters, use a special variable called `_request_parameters`. This is something
that EasyAdminBundle gives us, and it contains all of the query parameters. You'll
see why that's cool in a minute.

But the *most* important thing is to add another query parameter called `action`
set to `export`:

[[[ code('0be64bc37b') ]]]

Oh boy, that's ugly. But, it works great: it generates a route to `easyadmin` where
`action` is set to `export` and all the existing query parameters are maintained.

Phew! Inside, add a download icon and say "Export":

[[[ code('8d36670dcb') ]]]

Try it! Woh! We have an export button... but nothing else. I *love* to forget the
`parent()` call:

[[[ code('4bde973811') ]]]

Try it again. Beautiful!

When I click export, it of course looks for `exportAction` in our controller...
in this case, `GenusController`. 

## Adding the Custom Action

Remember: we're not going to support this export action for all of our entities.
And to make this error clearer, open `AdminController` - our base controller - and
create a `public function exportAction()` that simply throws a new `RuntimeException`:
"Action for exporting an entity is not defined":

[[[ code('65f3a1f228') ]]]

If we configure everything correctly, and implement this method for all entities
that need it, we should never see this error. But... just in case.

Now, to the *real* work. To add an export for genus, we have two options. First,
in `AdminController`, we *could* create a `public function exportGenusAction()`. Remember,
whenever EasyAdminBundle calls *any* of our actions - even custom actions - it
always looks for that specially named method: `export<EntityName>Action()`. *Or*, we
can be a bit more organized, and create a custom controller for each entity. That's
what we've done already. So, in `GenusController`, add `public function exportAction()`:

[[[ code('e42abd5375') ]]]

## Adding the CSV Export Logic

To save time, we've already done most of the work for the CSV export. If you downloaded
the starting code, in the `Service` directory, you should have a `CsvExporter` class:

[[[ code('590263197a') ]]]

Basically, we pass it a `QueryBuilder`, an array of column information, or the
entity's class name - which is mapped to an array of column info thanks to this
special function, and the filename we want. Then, it creates the CSV and returns
it as a `StreamedResponse`. So all we need to do is call this method and return it
from our controller!

I'll paste a little bit of code in the action to get us started:

[[[ code('46475b3e4c') ]]]

When we created the export link, we kept the existing query parameters. That means
we should have a `sortDirection` parameter... which is a nice way of making the export
order match the list order.

To create the query builder, we can actually use a protected function on the
base class called `createListQueryBuilder()`:

[[[ code('7a0492dcf8') ]]]

Pass this the entity class, either `Genus::class` or `$this->entity['class']`...
in case you want to make this method reusable across multiple entities:

[[[ code('fb2c153e36') ]]]

Next, pass the sort direction and then the sort field: `$this->request->query->get('sortField')`:

[[[ code('c0cd547cf6') ]]]

Finally, pass in the `dql_filter` option: `$this->entity['list']['dql_filter']`:

[[[ code('15d184f1dc') ]]]

This is kind of cool. We're using the `entity` configuration array - which is always
full of goodies - to actually read the `list` key and the `dql_filter` key below
it. If we have a DQL filter on this entity, the CSV export will know about it!

Ok, *finally*, we're ready to use the `CsvExporter` class. Because I'm using the
new Symfony 3.3 service configuration, the `CsvExporter` is already registered as
a *private* service:

[[[ code('d05b6e6dfb') ]]]

## Using DI in a Fake Action

The Symfony 3.3 way of accessing a service from a controller is as an argument to
the action. But... remember: this is *not* a real action. I mean, it's not called
by the normal core, Symfony controller system. Nope, it's called by EasyAdminBundle...
and none of the normal controller argument tricks work. You can't type-hint the Request
or any services.

Because of this, we're going to use *classic* dependency injection. We can do this
because this controller - well *any* controller if you're using the Symfony 3.3
configuration - is registered as a service. Add a `__construct()` function and
type-hint the `CsvExporter` class. I'll press `Alt`+`Enter` to create a property and
set it:

[[[ code('feddb137bc') ]]]

Back down below, just return `$this->csvExporter->getResponseFromQueryBuilder()`
and pass it the `$queryBuilder`, `Genus::class`, and `genuses.csv` - the filename:

[[[ code('cba29c26ce') ]]]

Deep breath... refresh! It downloaded! Ha! In my terminal. I'll:

```terminal-
cat ~/Downloads/genuses.csv
```

There it is!

## Hiding the Extra 

There's just *one* last problem: on the list page... we still have those weird
export links on each row. That's technically fine... but it's super confusing. The
*only* reason we added this `export` action was so that it would be a valid action
to call:

[[[ code('e633f7ddeb') ]]]

Unfortunately, this *also* gave us those links!

No worries, we just need to hide that link manually... and we already have a filter
to do this! Open `EasyAdminExtension` and `filterActions()`. Now, just unset
`$itemActions['export']`. That looks a little crazy, so I'll add a comment: "This
action is rendered manually":

[[[ code('8c14db6a08') ]]]

Try it! Yes! We have the export button on top... but *not* on each row. This is a
tricky - but *valid* - use-case for custom actions.


[eddie]: https://twitter.com/SouthernRailUK/status/884781072906690561
