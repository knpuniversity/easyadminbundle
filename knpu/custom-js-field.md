# Custom Fields with JavaScript

99% of stuff in EasyAdminBundle is really easy. But it's that last, pesky 1% that
can be so tough! And usually, that last 1% involves a form.

Sometimes, you have a form field that needs to be *really* customized. Maybe you
need to write a bunch of special JavaScript, your own HTML or even create some custom
routes and make AJAX calls back to them. Well... so far, all we can do is... just
use the built-in form field types. And while that system is super extensible, it
can also be super complex. So, we're going to dive into two examples where we do
something very custom, without pulling our hair out.

## Adding the JS Markdown Field

On the front-end of our site, this `funFact` field is processed through Markdown.
That means we can *totally* use Markdown syntax inside its textarea. But... our admin
users aren't very comfortable with Markdown... and being the awesome programmers
that we are, we want to help them! I want to embed a JavaScript markdown previewer
library called Snarkdown. You type some markdown, and Snarkdown shows you a preview.

So how can we transform our boring `textarea` field to include this?

In `config.yml`, under `Genus`, find the `funFact` field and add a `css_class` option
set to `js-markdown-input`. This will be our new best friend. Because now that the
element has this CSS class, we can write JavaScript to do *whatever* insane crazy
things we want!

How do we include JavaScript on the page? We already know how! Up at the top, under
`design` and `assets`, add `js`. Let's add 2 JavaScript files. First, include the
Snarkdown library: we could also download it locally. And include a new
`js/custom_backend.js` file.

To save some time, if you downloaded the code that came with this tutorial, you'll
have a `tutorial/` directory with a `custom_backend.js` file inside. Copy that, go
into `web/js` and paste. It's pretty simple: inside a `$(document).ready()` block,
it finds all the `js-markdown-input` classes, gets their `.form-control` element,
adds a new `markdown-preview` div and then processes that through Snarkdown. Basically,
it creates an element that will show the preview version of our Markdown.

In that same `tutorial/` directory, there's also a `custom_backend.css` file. Just
copy its contents. Open our `custom_backend.css` file and paste at the bottom... to
make things *just* a little prettier.

I think we're ready! Refresh the page. Bam! There's our preview below the field.
We can add some bold... and use some ticks. We rock!

I want you to realize how *powerful* this is. You can easily add a css class to any
field. And then, by writing JavaScript, you can do *anything* you want! You could
render a text field, hide it and then *entirely* replace the area with some crazy,
custom JavaScript widget that updates the hidden text field in the background. This
is your Swiss Army Knife.

In fact, we're going to do something similar next.
