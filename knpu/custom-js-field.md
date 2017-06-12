# Custom JS Field

Thanks to easy admin bundle, 99% of what you need to do is really, really easy. That last 1% sometimes can be very difficult. And usually it involves a form. Like some form fields were you need to something really, really custom, and you just want to write some HTML JavaScript, maybe even some custom routes and controllers and do the job yourself. But, so far, all we can do is just use the built-in form field types. And while that form system is very, very extensible, it can also be very complex. So I'm going to show you two examples where you can do something very, very custom without pulling your hair out.

On the front end of our site, this fun fact field is actually run through Markdown. So we can actually put Markdown code inside of here. However, our admin user might not be that comfortable with Markdown, so we want to help them out. I want to give them a little Markdown previewer. I'm going to use a library called Snarkdown. Which you can see an example of on this page. You can actually mess with some stuff right here, and you'll get a little preview window just to the right of it. Is that the right one? Yeah that's fine.

Now you have a text box and it just renders it immediately to the right. Pretty awesome. Cool, so how can we do that? In some ways, because we're going to need to write some custom JavaScript, and this is going to become a much more fancy widget. So the easiest way to do it is just to ... So there's actually a really easy way to do it. Inside of our admin configuration, under genus, find fun fact form field and add CSS class js-markdown-input. That is your friend. We've now tagged that element with a CSS class, which means we can write JavaScript to do whatever the heck we want to do to that field.

How do we include JavaScript on the page? We already know how. Up at the top, there's an assets section under design. We're going to add a js key, and we're actually going to add two things here. First I'm going to add a link to the Snarkdown library, which we could have also download locally. And then I'm going to add a new js/custom_backend.js file. Just like our custom css back.js

Now to save us a little bit of time, if you download the code that came with this tutorial, you'll have a tutorial directory with a custom_backend.js. I'm going to copy that and go into the js directory and paste that there. So you can see it's pretty simple. It uses the document.ready. It finds all the js Markdown input, find their form control, creates a Markdown preview, and then runs that through Snarkdown. So it's basically setting up a little div element that's going to preview whenever we change anything inside of our Markdown field.

In that tutorial directory there's also a custom_backend.css. Just copy its contents. Open our custom_backend.css and paste those in the bottom. So that's going to be a little bit of, it's going to help make it a little more obvious what that preview area is doing.

And, that's it. Refresh the page. Bam. There's our preview down there. You can add some bold. You can do a cross out. Or not. Use some tics, and we've got it. I want you to realize how powerful that is. You can easily add a css class to any field. And then, write custom JavaScript to do anything you want with that field. You can render a text field and entirely replace that text field with some crazy custom JavaScript widget that updates the hidden text field in the background. This is your Swiss Army Knife for adding really custom stuff to your form.



