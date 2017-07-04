# Installation and First Admin

Hello! And welcome to another *amazing* episode of: "Recipes with Ryan". Today
we'll be baking a positively mind-blowing, mile-high cinnamon bread!

Huh? It's not the recipe video? EasyAdminBundle? Ok, cool!

Um... we'll be baking a positively mind-blowing admin interface in Symfony
with the wonderful EasyAdminBundle.

## What about SonataAdminBundle

Wait, but what about SonataAdminBundle? SonataAdminBundle *is* super powerful... more
powerful than EasyAdminBundle. But it's also a bit of a beast - a lot bigger and
more difficult to use. If it has a feature that you *need*... go for it!
Otherwise, jump into the easy side with EasyAdminBundle.

## Code with Me!

To make your cinnamon bread a hit, code along with me. Download the course code
from this page and unzip it. Inside, you'll find a directory called `start/`,
which will have the same code you see here. Open `README.md` for step-by-step
baking instructions... and also details on how to get your project setup!

The last step will be to find your favorite terminal, move into the project and run:

```terminal
php bin/console server:run
```

to start the built-in web server. Find your fanciest browser and open that:
http://localhost:8000. Welcome to AquaNote! The project we've been building in
our Symfony series.

Actually, in that series, we spent some serious time making an admin area for one
of our entities: Genus. Go to `/admin/genus`... and then login: username `weaverryan+1@gmail.com`,
password: `iliketurtles`.

A genus is a type of animal classification. And after all our work, we can create
and edit them really nicely.

That's great... but now I need an admin interface for a bunch of other entities:
`GenusNote`, `SubFamily`, and `User`. Doing that by hand... well... that would
take a while... and we've got baking to do! So instead, we'll turn to EasyAdminBundle.

## Installing EasyAdminBundle

Google for EasyAdminBundle and find its GitHub page. Ah, it's made by our friend Javier!
Hi Javier! Let's get this thing installed. Copy the `composer require` line, go back
to your terminal, open a new tab, and paste:

```terminal
composer require javiereguiluz/easyadmin-bundle
```

While Jordi is downloading that package, let's keep busy!

Copy the new bundle line, find `app/AppKernel.php`, and put it here!

[[[ code('3d0f3f125b') ]]]

Unlike most bundles, this bundle actually gives us a new *route*, which we need
to import. Copy  the routing import lines, find our `app/config/routing.yml` and
paste anywhere:

[[[ code('d0c7fb3804') ]]]

Since we already have some pages that live under `/admin`, let's change the prefix
to `/easyadmin`:

[[[ code('ec6ff21bca') ]]]

*Finally*, one last step: run the `assets:install` command. This should run automatically
after Composer is finished... but just in case, run it again:

```terminal
php bin/console assets:install --symlink
```

This bundle comes with some CSS and JavaScript, and we need to make sure it's available.

## Setting up your First Admin

Ok, we are installed! So... what did we get for our efforts? Try going to
`/easyadmin`. Well... it's not much to look at yet... but it will be! I promise!
We just need to configure what admin sections we need... and Javier gave us a great
error message about this!

In a nut shell, EasyAdminBundle can create an admin CRUD for any entity with almost
zero configuration. In the docs, it shows an example of this minimal config. Copy
that, find `config.yml`, scroll to the bottom, and paste. Change these
to our entity names: `AppBundle\Entity\Genus`, `AppBundle\Entity\GenusNote`,
`AppBundle\Entity\SubFamily`, skip `GenusScientist` - we'll embed that inside one
of the other forms, and add `AppBundle\Entity\User`:

[[[ code('2834bccd32') ]]]

We have arrived... at the moment of truth. Head back to your browser and refresh
Ah, hah! Yes! A full CRUD for each entity: edit, delete, add, list, show, search,
party! For a wow factor, it's even responsive: if you pop it into iPhone view, it
looks pretty darn good!

This is exactly what I want for my admin interface: I want it to be amazing and
I want it to let me be lazy!

Of course the trick with this bundle is learning to configure and extend it. We're
80% of the way there with no config... now let's go further.
