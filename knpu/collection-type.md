# Collection Type

The form uses field type guessing to guess default types from most of our field. And usually it's right, but it's not always right. Genus scientist as an example of when it's not right. For example, if I ... I'll open the clipboard icon down here to go to the form profiler.

If you go down to the genus scientist, you can see it guess this as an entity type with multiple true. So in easy admin bundle, it's this cool kind of auto complete thing, and in normal [inaudible 00:00:57] of this would be for example, check boxes. Well that's not right because genus scientist is a one to many to a genus scientist entity, and that actually has an extra field on it called year study.

In Symfony's years, we actually went to a lot of work to create a collection type, where we actually see embedded genus scientist forms in here where we can select the user and enter the number of years studied. So we've already done all of that set up and all that work, and now we just need to update our form to actually use it.

In other words, if you look at genus form type, this is the form that we're using in our custom admin section. And we've set up the genus scientist as a collection type. So since we've already done all that work, we can just reflect that in our section here. I'll use the expanded syntax for the genus scientist property. I'll set type to collection, and I'll set type options to the four options that we see here. So that's app bundle slash form slash genus scientist, embedded form, allow delete true, allow add true, and by reference false.

And now when I refresh, it works. We can remove items, we can add new items, and life is good. Well, there are a couple problems with this form. One is that when we created this form for our custom admin area, we actually hid the user field in edit, which looks really funny now. So I'm actually going to go into genus scientist embedded form, and we used a form event for that, so when the genus scientist had an ID, we unset the user field. I'm gonna uncomment that now, refresh this form so it makes a little bit more sense.

Now two important things about this. First is, it's ugly. So you're probably going to need to create a custom form theme for the collection type and render this in a more intelligent way. We're going to do something similar to that in a few minutes.

Second, this only works because we've done all the work of setting up our relationships correctly. The collection type in symfony is simultaneous the best form type and the worst form type. Because it allows you to do really complex stuff like this, but only if you have all of your relationships set up correctly. You need to worry about the owning and the inverse side of relationships and things called orphan removal and cascading. There's some complex doctrine things behind this to get it to work.

So in a few minutes, I'm going to show you a more custom alternative option to using the collection type if you have this situation and this isn't working for you. But first, I'm going to show you one more thing with normal forms. And that's on our user field. So I'm going to go to user entity, I'll edit a user, and so far, we haven't done any configuration here. So it's just guessing everything.

In config dot yml, down under the user, I'm going to add our form config, fields, and then I'm going to add email. Let's take control of this. Is scientist ... and then I'm actually going to customize email and is scientist to start. And notice we have first name and we have last name in the form originally. That's because we have first name and last name properties inside of our user. Similar to what we did with the list view, if we want to, instead of having a first name and a last name, we can actually have a full name field. We don't have a full name property, but we do have a get full name function. And as long as we have a set full name function, then we can add it to our form as if it were a field. This is not special to the easy admin bundle, this is just how symfony forms system works.

Now this example might be a little bit dangerous. I'll add some config that will split the names up. Take everything before the first space as the first name, everything after as the last name, and then we'll call a set first name and set last name. Probably not a perfect algorithm, but you guys get the idea.

So now we have a get full name and a set full name in that config. We're actually going to set that as a property. Property full name, type text, in help first then last. Now continue by adding avatar uri, and university name. Beautiful.

Refresh that, and now we've got it. And it even submits.

