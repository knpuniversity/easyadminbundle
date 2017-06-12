# Custom Actions

Let's talk about custom actions, because as we know there are a bunch of built-in actions like delete and edit, but sometimes you need to manipulate an [entity 00:00:08] in a different way. For example, what if we want a button to publish a [genus 00:00:15]?

There are actually two different ways to do that. First, I'm going to go to a genus show page. On the show page, the actions show down here. Let's say we want to add a new button down here, because we want to feed our genus. We're going to click the feed button, and it's going to go off to some customer controller, do some work, and then send us back.

The first part should feel very natural. We already know how to customize our actions. We can add actions, and we can even remove actions if we don't want them. Under genus, anywhere really, I'm going to customize the show view for the first time. We'll say actions here. Instead of just listing an action, we're going to use a bigger config, where we create a custom action. We're going to set its name to genus feed and it's type to route.

There are two different ways to add custom actions. One is route-based, so we actually just give a route that it sends it to, and another one is action-based, which is a little bit simpler, where we just need to write an action. We don't need a route. This accepts all the normal options that you'd expect when you're customizing an action. You get a label. We'll give it a CSS class. BTN. BTN-info. We'll even give it an icon.

Next thing we need to do is actually create that endpoint, so I'll go in SRC app on the controller, open my normal genus controller, and right on top we're going to add that. I'll call it feed action. How about /genus/feed. Then genus_feed as the route name. To match the genus_feed that we have right here. Notice the URL for this is just /genus/feed, so this is not protected via our access control, so if you want to add security to this, you need to add it manually.

That actually should be enough. We refresh right now. There is our button. Click it and ... Good. Error. This is what we expected, because our action is empty. The question is, if we just clicked feed on one specific genus, so somehow the bundle must be passing us the ID of the genus here, and it actually happens via query parameters, which are a little hard to read up here, but if you click any link down here in your profiler, and go to request/response, here are the GET parameters, and you can very easily see there's an entity key and also an ID key, which we can use to get that.

Once we know where to get the information we need, this ends up being a very traditional controller. I'll type in the request object from [HD 00:03:40] to foundation. Then we'll get the entity manager with the ID from request. Request>query>get, since it's a query parameter. Then we'll say genus equals EM get repository. The genus, then find ID.

Now for the logic of feeding this, actually we, in a previous tutorial created a fun feed method instead of our genus, so we're going to use that. I'll create a little menu here. [get a meal 00:04:35]. Then we'll just set a flash message. Easy and a bundle supports several different flash types out of the box. Genus>feed. Meal. Perfect.

Now to redirect back to easy admin bundle, we're going to use return, this arrow, redirect route. In easy admin bundle is actually only one route. It's called easy admin. The way you tell it where to go is via query parameters. Most important one being action, show. [inaudible 00:05:27] which entity we're using, which I'll just use request arrow query arrow get entity. We could just pass it genus. Then ID set to our ID. That is it. Now I'll refresh this feed page, and we've got it. Hit that over and over again, keep feeding our genus. That's the first way to add a custom action.

The second way is similar, but a little bit different. [Inside config 00:06:04] [inaudible 00:06:07] element add is another action down here. This one I'll say the name is going to be change published status, instead of CS class to just button, and that's all we need.

For refresh, we're going to see that button immediately. When we click it, we get an error. Warning, call user [funk 00:06:39] expects parameter one be a valid callback. Admin control does not have a method change published status action. When you use this format, it's actually looking for a change published status action on the admin controller, the one that comes with the bundle.

To use this method, we actually need to subclass the admin controller, and then add that method. It's actually a pretty handy thing to do. In controller I'm going to create a new directory called easy admin. Inside of there, a new PHP class called admin controller. We're going to make this extended normal admin controller, so add a use statement for admin controller, for the one for easy [admin 00:07:23] bundle, and alias it to base admin controller. Then we'll extend base admin controller.

Now we can do a fairly traditional action method here. Change published status action, because in config [dot Y 00:07:48] now we call it change published status, so it has the action on the end of it.

Now the way that you get ... This looks and feels very much like a normal action. There's one special thing though. This method is going to be called buy easy admin bundle. It's not actually called by Symphony's routing system, which means you can't actually type in the request argument here. It doesn't work. Instead, the base admin controller has a number of protected variables on it, including the entity manager, and the request, and extra configuration. You actually need to use this arrow request to get the request object, because of that.

Our entity will say ID equals this arrow request arrow query arrow get. Then we're going to day entity equals this arrow, EM, get repository, for the entity, for the genus, and then find that ID. Almost toggle its entity status. Say this arrow EM arrow flush. We'll even add a flash that says genus percent S published with a little fancy logic here to say genus published or genus unpublished.

Finally, same thing at the bottom, where we want to return back to the show page. I'm actually going to copy that from genus controller. The one difference of course being it's not request, but this arrow request. Everything else should be exactly the same.

Let's refresh that page. And whoa, it still doesn't work. The exact same error actually. That's because we haven't told Symphony to use our admin controller yet. It's still using the base one from the repository. The way to fix this is actually in your routing file. App config routing dot ML. We tell it to import all of the annotation routes from the easy admin bundle slash controller directory. Actually I'm going to change that to the at bundle slash controller slash easy admin slash admin controller. Basically we'll now point it at our controller. Our controller will become the official controller, but because we're extending the base controller, it's still going to pick up all of the normal annotation routes that it would have before.

That's enough for it to see our new method. Boom. Genus published. Again, for genus unpublished. Got it.

