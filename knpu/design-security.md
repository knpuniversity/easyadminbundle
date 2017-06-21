# Design Config & Security Setup

With about six lines of code, we got a fully functional admin area. It must be our
birthday. But now... we need to learn how to take control and *really* customize!

And for most things... it's... um... easy... because EasyAdminBundle let's us control
almost anything via configuration. Back on its README, scroll up and click to view
the full docs.

The bundle has great docs, so we'll mostly cover the basics and then dive into the
really tough stuff. Let's start with design.

Right now, in the upper left corner, it says, "EasyAdmin"... which is probably
*not* what I want. Like most stuff, this can be changed in the config. Add a `site_name`
key set to `AquaNote`. Actually, to be fancy, add a bit of HTML in this.

Refresh! Sweet! What else can we do?

## The design Config Key

Well, eventually, we'll learn how to override the EasyAdminBundle templates... which
pretty much means you can customize anything. But a lot of things can be controlled
here, under a `design` key.

For example, `brand_color`. This controls the blue used in the layout. Set it to
`#819b9a` to match our front-end a bit better. Try that! Got it!

But what if we need to change something more specific... like if we want the branding
name to be a darker gray color? Let's see... that means we want to set the color
of the `.main-header .logo` anchor tag. So... how can we do that? The way you
normally would: in CSS. Create a new file in `web/css`: `custom_backend.css`. Add
the `.main-header .logo` and make its color a bit darker.

Simple... but how do we include this on the page... because we don't have control over
any of these templates yet. Well, like most things... it's configurable: add
`assets`, `css` then pass an array with the path: `css/custom_backend.css`. 

And yes! There *is* a `js` key and it works the same. We'll use it a bit later.
Refresh to see our sweet styling skills. Woohoo!

There are a few other keys under `design` and we'll use some of them. But they're
all pretty simple and this stuff is documented under "Basic configuration" and
"Design Configuration".

## Adding Security

Before we keep going... we need to talk security! Because right now, I can log out
and go back to `/easyadmin` with no problem. This is *totally* open to the public.
Fun!

How can we configure security in EasyAdminBundle? We don't! This is just a normal
page... so we can use Symfony's normal security to protect it.

The easiest way is in `security.yml` via `access_control`. Uncomment the example,
use `^/easyadmin` and check for `ROLE_ADMIN`. That is it!

When we refresh... yep! We're bounced back to the login page. Log back in with
`weaverryan+1@gmail.com` password `iliketurtles`. And we're back! We *can* also secure
things in a more granular way... and I'll show you how later.

Now, let's start customizing the list page.
