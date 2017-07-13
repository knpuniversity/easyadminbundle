## Customize Template for One Field

We already customized the template used for *every* field whose data type is id.
But you can also go deeper, and customize the way that just *one* specific field
is rendered.

For example, let's say we need to customize how the "full name" field is rendered.
No problem: in `config.yml`, find `User`, `list`, `fields`, and change `fullName`
to the expanded configuration. To control the template add... surprise! A `template`
option set to, how about, `_field_user_full_name.html.twig`:

[[[ code('a3e13f313f') ]]]

Copy that name. It expects this to live in the standard `easy_admin` directory.
Create it there!

Since this is a template for one field, it will have access to the `User` object as
an `item` variable. And that makes life easy. Add `if item.isScientist` - that's
a property on `User` - then add a cool graduation cap:

[[[ code('7569dfc6ae') ]]]

Below, print the full name. To do that, you can use a `value` variable. Pipe it
through `|default('Stranger')`, just in case the user doesn't have any name data:

[[[ code('e8feea03d6') ]]]

Try it! Yes! We now know how to customize *entire* page templates - like `list.html.twig`,
templates for a specific field *type*, or the template for just *one* field. 

Time to move into forms!
