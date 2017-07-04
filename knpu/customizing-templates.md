# Customize all the Templates!

We can do *a lot* via config... but eventually... we're going to need to *really*
dig in. And that will probably mean overriding the templates used by the bundle.

## Exploring the Templates

First, let's go look at those templates! Open `vendor/javiereguiluz/easyadmin-bundle/Resources/views/default`.
Ah, ha! These are the *many* templates used to render every single part of our admin.
We can override *any* of these. But even better! We can override any of these for
specific entities: using different customized templates for different sections. Or
even... different templates to control how individual *fields* render.

Check out `layout.html.twig`... this is the full admin page layout. It's awesome
because it's *filled* with blocks. So instead of *completely* replacing the layout,
you could extend this and override only the blocks you need. We won't do that for the
layout, but we will for `list.html.twig`.

This is responsible for the `list` view we've been working on. And not surprisingly,
there are also new, show and edit templates.

But *most* of the templates start with ``field_``... interesting. Remember how each
field on the list page has a "data type"? We saw this in the EasyAdminBundle configuration.
The "data type" is used to determine which template should render the data in that
column. `firstDiscoveredAt` is a `date` type... and hey! It has a `template`
option that defaults to `field_date.html.twig`. And by opening that template, you
can see how the `date` type is rendered.

## How to Override Templates

Ok, let's *finally* override some stuff! How!? On the same
[List, Search and Show Views Configuration](http://symfony.com/doc/current/bundles/EasyAdminBundle/book/list-search-show-configuration.html)
page, near the bottom, you'll see an "Advanced Design Configuration" section.
There are a *bunch* of different ways to override a template... ah... too many options!
Let's simplify: (A) you can override a template via configuration - which are options
1 and 2 - or (B) by putting a file in an `easy_admin` directory - options 3 and 4.
We'll try both.

Ok, first challenge! I want to override the way the `id` field is rendered for
`Genus`: add a little `key` icon next to the number... ya know, because it's the
primary key.

This means we need to override the `field_id.html.twig` template, because `id` is
actually a data type. Copy `field_id.html.twig`. Then, in `app/Resources/views`,
I already have an `admin` directory. So inside that, create a new `fields` directory
and paste the file there, as `_id.html.twig`. Now, add the icon: `fa fa-key`.

Cool! I put the file here... just because I already have an `admin` directory.
But EasyAdminBundle doesn't automagically know it's there. Nope, we need to tell
it. In `config.yml`, to use this *only* for `Genus`, add a `templates` key, then
`field_id` - the name of the original template - set to `admin/fields/_id.html.twig`.

Try that! Yes! It *is* using our template... and only in the `Genus` section.
But this key thing is pretty excellent, so we should use it everywhere. Copy
the templates config and comment it out. Just like with almost anything, we can
also control the template globally: paste this under `design`.

Now the key icon shows up *everywhere*.

Next, I want to override something bigger: the entire list template. And we'll
use a different convention to do that.
