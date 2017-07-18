# Dashboard & Menu Customizations

The *only* thing we have *not* talked about is this big, giant menu on the left!
This menu is *actually* the key to one other commonly-asked question: how do I create
an admin dashboard?

The answer... like always... lives in the configuration! In `config.yml`, under
`design`, add a `menu` key. This works like many other config keys. First, it has
a simple format: just list the sections in the order you want them: `User`, `Genus`,
`GenusHorde` and `SubFamily`. These keys are coming from the keys that we chose for
each section's configuration. These could have been anything.

Thanks to this, the `User` link will move from the bottom all the way to the top.
There are a *lot* of other customizations you can make to the menu... but before
we get there, I want a dashboard! Yea know, an admin homepage full of important-looking
graphs!

## Adding a Dashboard

If you downloaded the course code, you should have a `tutorial/` directory. Inside,
it has an `AdminController` with a `dashboardAction`. Copy that. Then, in
`src/AppBundle/Controller/EasyAdmin`, open our `AdminController` and paste it there.

Thanks to the prefix on the route import, this creates a new `/easyadmin/dashboard`
route named `admin_dashboard`. Oh, I'm missing my `use` statement for `@Route`. I'll
re-type that and hit enter so that it auto-completes the `use` statement on top.
Perfect!

This renders a template, which I will *also* grab from the `tutorial/` directory.
Paste that in `app/Resources/views/easy_admin`.

At this point... the page *should* work. Cool... but how can I tell EasyAdminBundle
to show this page when we go to the admin section's homepage? Right now, if you go
directly to `/easyadmin`, it will take you to whatever the first-defined entity
section is... so Genus.

## Adding the Dashboard Menu Link

But... add a new menu item and use the *expanded* config format with `label: Dashboard`,
`route: admin_dashboard` and - here is the key - `default: true`.

Thanks to `default: true`, when you click on the AquaNote logo to go to the admin
homepage... ah! You'll get an error! That was *not* the dramatic success moment I
was hoping for.

But... look! It *did* redirect to `/easyadmin/dashboard`! The error is just a Ryan
mistake: I forgot a `use` statement for my `Genus` class. Add that on top.

Try it again! Hello super fancy dashboard! Where apparently, somehow, interest
in funny cat videos has been *decreasing*. Well, anyways, say hello to your new
dashboard. Where hopefully, you will build infinitely more useful graphs than this.

Now, back to customizing that menu...
