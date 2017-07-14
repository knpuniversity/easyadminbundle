# The Autocomplete Field

EasyAdminBundle re-uses all the form stuff... but also comes with *one* *new* form
field... and it's pretty bananas! From the main documentation page, click
[Edit and New Views Configuration][edit_new_configuration]. Down a ways, find
a section called "Autocomplete". Ah, lovely! This is a lot like the `EntityType`...
except that it renders as a fancy AJAX auto-complete box instead of a select drop down.

Right now, the `subFamily` field is a standard `EntityType`. But, it doesn't look
that way at first... it's fancy! And has a search! We get this automatically thanks
to some JavaScript added by EasyAdminBundle. It works *wonderfully*... as long as
your drop down list is short. Because if there were hundreds or thousands of sub
families... then *all* of them would need to be rendered on page load... which will
*really* slow - or even break - your page.

Let's use the autocomplete field instead. Expand the `subFamily` configuration and
set `type: easyadmin_autocomplete`:

[[[ code('754e3c4317') ]]]

That is *all* we need: it will look at the `subFamily` field and know which entity
to query. So.... it just works! Watch the web debug toolbar as a I type. Ha!
There be AJAX happening!

Next, let's add a `CollectionType` to our form.


[edit_new_configuration]: http://symfony.com/doc/current/bundles/EasyAdminBundle/book/edit-new-configuration.html
