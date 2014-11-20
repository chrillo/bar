$(function(){

/*
	small helper to format prices;
*/
var utils={
	formatPrice:function(price){
		return (Math.floor(price*100)/100).toFixed(2)
	}
}


/* 
All the html templates
*/
var helpTemplate="<h2>1.) I forgot my pin code</h2><p>Please ask Yves or Christoph to reset it for you.</p>";
	helpTemplate+="<h2>2.) I don't have pin code yet</h2><p>See previous answer.</p>";
	helpTemplate+="<h2>3.) How do I use this?</h2><p>Login with your pin. Tap on the items you took from the bar and click the confirm order button. The items are then added to your bar tab and you can pay for them at a later point in time.</p>";
	helpTemplate+="<h2>4.) I accidentally put a wrong item on my tab</h2><p>Yves can remove stuff for you. You should generally check your order before tapping the confirm button.</p>";
	helpTemplate+="<h2>5.) I want pay my bar tab</h2><p>Talk to Yves or Christoph</p>";
	helpTemplate+="<small class='credits'>created by @christoph_peter with Fuelphp, underscroe.js, backbone.js, jQuery.js,keymaster.js & ICanHaz.js. Code on github https://github.com/chrillo/Bar</small>"
 
var itemTemplate="<span class='item-name'>{{title}}</span><span class='item-price'>{{price}}€</span><span class='item-count'>{{user_count}}</span>";
var trayItemTemplate="<span class='item-name'>{{title}}</span><span class='item-price'>{{price}}€</span>";

var orderTemplate="<span class='button order {{css}}'><span class='order-text'>{{text}}</span><br/><small>{{items}} item{{s}} for <span class='tray-price'>{{price}}</span>€</small> </span>";

var bartabTemplate="<span class='user-name'>Hi, {{username}}! Your bar tab is:</span><span class='bar-tab'><span>{{price}}€</span></span>";
var headerTemplate="<span class='categories'></span>{{^user}}<span class='locked button'>Login</span>{{/user}}<span class='help button'>Help</span>{{#user}}<span class='exit button'>Exit</span>{{/user}}";


var consumptionTemplate="<span class='consumption-title'>{{title}}</span><span class='consumption-count'>({{count}})</span><span class='consumption-sum'></span> for {{price}}<span class='consumption-items'></span>";	
	
var lockTemplate="<div type='text' class='pin-input'></div><span class='num-key'>1</span><span class='num-key'>2</span><span class='num-key'>3</span><span class='num-key'>4</span><span class='num-key'>5</span><span class='num-key'>6</span><span class='num-key'>7</span><span class='num-key'>8</span><span class='num-key'>9</span><span class='num-key'>0</span><span class='button clear-key'>clear</span><span class='button enter-key'>enter</span><div class='clear'></div>"

/* 
add the templates to iCanHaz
*/

ich.addTemplate("help",helpTemplate);
ich.addTemplate("bartab",bartabTemplate)
ich.addTemplate("consumption",consumptionTemplate)
ich.addTemplate("lock",lockTemplate);
ich.addTemplate("order",orderTemplate);
ich.addTemplate("item",itemTemplate);
ich.addTemplate("trayItem",trayItemTemplate);
ich.addTemplate("header",headerTemplate);

/*
	define the models for User, Category and Item
	Items has a method to increase the user consumption count

*/

var UserModel = Backbone.Model.extend({})
var CategoryModel=Backbone.Model.extend({});
var ItemModel = Backbone.Model.extend({
	initialize:function(){_.bindAll(this,"countUp","reset")},
	countUp:function(silent){
		if(this.get("user_count")>0){
			this.set({"user_count":this.get("user_count")+1},{silent:silent})
		}else{
			this.set({"user_count":1},{silent:silent})
		}
	},
	reset:function(){
		
		this.set({"user_count":""});
	}
})

/*
	define the two main collections that hold the categories and the items
*/

var ItemCollection = Backbone.Collection.extend({model:ItemModel});
var CategoryCollection = Backbone.Collection.extend({model:CategoryModel});


/*
	the tray view on the side
	holds a lot of the apps logic
*/

var TrayView = Backbone.View.extend({
	className:"tray",
	events:{
		'click .order':'placeOrder',
		'touchstart .order':'placeOrder'
	},
	initialize:function(){
		_.bindAll(this,"render","appendItem","placeOrder","addItem","removeItem",'clear',"orderComplete","updateBarTab","updateOrder")
		app.bind("lock",this.clear)
		app.bind("removeFromTray",this.removeItem)
		app.bind("addToTray",this.addItem)
		app.itemCollection.bind("change",this.updateBarTab)
		this.items=[];
		this.price=0;
		this.bartab=0;
		this.saving=false;
		
	},
	/*
		 updates the text and style of the order button
	*/
	updateOrder:function(){
		var s=""; 
			if(this.items.length!=1){s="s";}
			var text="Add items to tray";
			var css
			if(this.items.length>0){
				text="Confirm order";
				css="items";
			}
			$('.order-wrapper',this.el).html(ich.order({css:css,text:text,price:utils.formatPrice(this.price),s:s,items:this.items.length}))
	},
	/*
		renders the order button and all items in the tray
	*/
	render:function(){	
		if(app.user){
			$(this.el).html(ich.bartab({username:app.user.get("username"),price:utils.formatPrice(this.bartab)}));
		}
		$(this.el).append("<div class='order-wrapper'></div>")
		this.updateOrder();	
		
		_(this.items).each(function(item){
				this.appendItem(item)
		},this)
		
		this.delegateEvents()
		return this;
	},
	/* 
		calculates the current bar tab of the logged in user
	*/
	updateBarTab:function(){
		this.bartab=0;
		_(app.itemCollection.models).each(function(item){
			if(item.get("user_count")>0){
			 this.bartab+=(item.get("price")*1*item.get("user_count")*1);
			}
		},this)
		this.render();
	},
	/* 
		adds an item to the tray
		and increments the total sum
	*/
	addItem:function(item){
		app.views.mainview.setTimer()
		this.items.push(item)
		this.price+=item.get("price")*1
		this.appendItem(item);
	},
	/*
		removes an item from the tray
		deductes its price from the total sum
	*/
	removeItem:function(item){
		for(i=0,n=this.items.length;i<n;i++){
			if(this.items[i]==item){
				this.price-=item.get("price")*1
				this.items.splice(i,1);
				break;
			}
		}
		this.updateOrder();
		//this.render();
	},
	/* 
		adds an item view to the tray view
		updates the order buttons state since it depends on the number of items
	*/
	appendItem:function(item){
		$(this.el).append(new ItemTrayView(item).render().el);
		this.updateOrder();
	},
	/* 
		checks if items are in the tray
		serializes all the item ids
		sends them to the server 
		and calls order complete with the server response
	*/
	placeOrder:function(){
		if(this.saving==true || this.items.length<1){return;}
		this.saving=true;

		$(".order-text",this.el).html("Saving order")
		var ids=[]
		_(this.items).each(function(item){
			ids.push(item.get('id'))
		},this)
		var pin=app.user.get("pin");
		var self=this
		app.views.mainview.setTimer()
		$.ajax({
			url: app.root+"api/placeorder",
			data:{items:ids.join(","),pin:pin},
  			context: document.body,
  			success: function(response){	
  			  self.orderComplete(response)
  			},
  			error:function(response){
  			  self.orderComplete(response);
  			}
  		})
	},
	/* 
		fed with the response from a server
		if the status is 200 the order went through ok
		it then increments the user consumption counts on the item models
		and triggers a change on those that have changed
		then it clears the tray
	*/
	orderComplete:function(response){
		if(response.status==200){
			this.saving=false;
			
			_(this.items).each(function(item){
				item.countUp(true)	
			})
			_(app.itemCollection.models).each(function(item){
				if(item.hasChanged()){
					item.change();
				}
			})
			this.clear()
		}
	},
	/*
		cancels the server call
		usually server call is way to fast
		TODO: implement method
	
	*/
	cancelOrder:function(){
		
	},
	/* 
		clears the traym and resets price and items
	*/
	clear:function(){
			this.price=0;
			this.items=[]
  			this.render();
	}
})

/* 
	Renders an Item in the tray
	On click / tab it removes the item from the tray
*/
var ItemTrayView = Backbone.View.extend({
	className:'tray-item',
	events:{
		'click':'removeFromTray',
		'touchstart':'removeFromTray'
	},
	initialize:function(model){
		_.bindAll(this,"render","removeFromTray")
		this.model=model;
		this.model.bind("change",this.render)
	},
	render:function(){
		$(this.el).html(ich.trayItem(this.model.toJSON()))
		return this
	},
	/*
		removes the item from the tray
	*/
	removeFromTray:function(event){
		var _this = this;
		app.trigger("removeFromTray",this.model)
		$(this.el).addClass("active").slideUp(300)
		
		
		event.preventDefault();
	}
	
})

/*
	this renders an item in a category view
	on click / tap it adds the item to the tray
 */
var ItemView = Backbone.View.extend({
	className:'bar-item',
	events:{
		'click':'addToTray',
		'touchstart':'addToTray'
	},
	initialize:function(model){
		_.bindAll(this,"render",'addToTray')
		this.model=model
		this.model.bind("change",this.render)
	},
	/* 
		adds an item to the trayviews items collection	
	*/
	addToTray:function(event){
		app.trigger("addToTray",this.model)
		event.preventDefault();
	},
	/*
		renders the current item
		if the models user consumption count changes
		we add the update class that has the css animation
	*/
	render:function(){
		var data=this.model.toJSON();
			console.log(this.model.get("user_count"), parseInt(this.model.get("maxusage")))
			if(this.model.get("user_count") > parseInt(this.model.get("maxusage")) && parseInt(this.model.get("maxusage"))>0){
				$(this.el).addClass('item-maxed')
			}else{
				$(this.el).removeClass('item-maxed')
			}
		var html=ich.item(data);
		$(this.el).html(html)
		if(this.model.hasChanged('user_count')){
			$(this.el).addClass('update');
			var self=this;
			setTimeout(function(){
				$(self.el).removeClass('update')
			},1000);
		}
		return this
	}
	
})

/* 
	this view holds all items of a specific category
	
*/
var CategoryView = Backbone.View.extend({
	className:'bar-category',
	initialize:function(){
		_.bindAll(this,'render','appendItem')
		
		this.collection=new ItemCollection();
		this.collection.bind('add', this.appendItem);
		this.collection.bind('reset',this.change); 
	},
	/* if the items collection changed we redraw */
	change:function(){
		//console.log("change bar")
		this.render();
	},
	/*
		creates a container and loops through the items 
		it adds the items with the right category id
		this is not the most efficient way to do this
		but its more flexible in case we want one item to be in more than 1 category
		alsot he lists re so short it really does not matter
	*/
	render:function(){
		$(this.el).html();
		$(this.el).addClass("category-"+this.model.get("id"));
		var self=this;
		if (self.model.get("favorit")) {
			var itemsFiltered = _.sortBy(_.filter( app.itemCollection.models, function(it, items) { return it.get("user_count")>0; }), function(si) { return si.get("user_count") * -1; });
			_.each(itemsFiltered, function(item) {
				self.appendItem(item);
			});
		} else {
			_(app.itemCollection.models).each(function(item){
				if(item.get("category_id")==self.model.get("id")){
					this.appendItem(item)
				}
			},this)
		}
		$(this.el).append(app.views.trayview.render().el)
		return this;
	},
	/*
		adds a new item view to the category view
	 */
	appendItem:function(item){
		//console.log("appendItem")
		$(this.el).append(new ItemView(item).render().el)
	}
})

/* 
	The bar view is a wrapper for all categories
	it manages all items and puts them into the right categories
	it also manages transition between categories
*/

var BarView = Backbone.View.extend({
	tagName:"div",
	className:"bar",
	
	/* this sets the  */
	initialize:function(){
		_.bindAll(this,'render','appendItem','change','nextCategory','prevCategory','categoryByIndex')
		/* grab the categories collection  */		
		this.collection=app.categoryCollection
		this.collection.bind('add', this.appendItem);
		this.collection.bind('reset',this.change); 
		this.collection.url="api/items"
		if(this.collection.models.length==0){
			this.collection.fetch()
		}
		this.categoryIndex=0;
		app.bind("resize",this.render);
		
		var self=this
		if(app.hasTouch){
		/* swipe support */		
			this.swiper=new Swipe(this.el);
			this.swiper.onswipeleft=function(){self.nextCategory()}
			this.swiper.onswiperight=function(){self.prevCategory()}
			/* we move along with the drag */
			this.swiper.onswipemove=function(delta){
				self.moveCategories(-1*self.pageWidth*self.categoryIndex+delta)
			}
			/* we snap back to the original position */
			this.swiper.onswipeend=function(){
				self.moveCategoriesAnimated(-1*self.pageWidth*self.categoryIndex,0.7)
			}
		}else{
		/* key support */
			key('right', function(){ self.nextCategory() });
			key('left', function(){ self.prevCategory() });
		}
	},
	/* calls render if the items collection changes */
	change:function(){
		this.render();
	},
	/* creates the wrapper and builds a view for each category */
	render:function(){
		$(this.el).html("<div id='barview' class='bar-items'><div class='bar-categories'></div></div>");
		
		this.pageWidth=$(window).width()*0.8;
		this.pageHeight=$(window).height()-60;
		$(this.el).height(this.pageHeight);
		_(this.collection.models).each(function(category){
			this.appendItem(category)
		},this)
		
		$(this.el).append(app.views.trayview.render().el)
		
		$('.bar-categories',this.el).css({width:(this.pageWidth*this.collection.models.length)+"px"});
		$('.bar-category',this.el).width(this.pageWidth).height(this.pageHeight);
		
		
		return this;
	},
	/* adds a new category view */
	appendItem:function(category){
		//console.log("appendItem")
		$('.bar-categories',this.el).append(new CategoryView({model:category}).render().el)
	},
	/* 
		loops through the categories the find its position
		and moves the categories container in the right place
	 */
	nextCategory:function(){
		this.categoryByIndex(this.categoryIndex+1)	
	},
	prevCategory:function(){
		this.categoryByIndex(this.categoryIndex-1)
	},
	/*
		opens a category by index
		used for the next and prev methods
		checks that index is within the bounds of the categories array
		grabs the model and navigates the app to the category
	*/
	categoryByIndex:function(index){
		this.categoryIndex=index;
		if(this.categoryIndex>=this.collection.models.length){
			this.categoryIndex=this.collection.models.length-1;
		}
		if(this.categoryIndex<0){
			this.categoryIndex=0;
		}
		var model=this.collection.models[this.categoryIndex]
		app.routers.mainrouter.navigate('bar/'+model.get("id"),true)
	},
	/*
		opens a category based on its is
		TODO: make this event based from the app router	
	*/
	openCategory:function(category_id){
		var i=0;
		_(this.collection.models).each(function(category){
			if(category.get("id")==category_id){		
				this.moveCategoriesAnimated(-1*this.pageWidth*i,0.5);
				this.categoryIndex=i;
			}
			i++;		
		},this)
	},
	moveCategories:function(x){
		$('.bar-categories',this.el).css("-webkit-transition", "none");
		$('.bar-categories',this.el).css("-webkit-transform","translate3d("+x+"px,0px,0px)");
	},
	moveCategoriesAnimated:function(x,time){
		$('.bar-categories',this.el).css("-webkit-transition", "all "+time+"s ease-out");
		$('.bar-categories',this.el).css("-webkit-transform","translate3d("+x+"px,0px,0px)");
	}
})
/* the helpview is just some html text on how to use the app */
var HelpView = Backbone.View.extend({
	initialize:function(){
		_.bindAll(this,'render')
	},
	render:function(){
		$(this.el).html(ich.help());
		return this;
	}
})
/* simple button in the header view that opens a category */
var CategoryBtn = Backbone.View.extend({
	className:'button',
	events:{
		'click' : 'openCategory',
		'touchstart': 'openCategory'
	},
	initialize:function(){
		_.bindAll(this,'render','openCategory')
	},
	/* navigate to the correct category */
	openCategory:function(){
		app.routers.mainrouter.navigate('bar/'+this.model.get("id"),true)
	},
	render:function(){
		
		$(this.el).html(this.model.get('label')).addClass("category-btn category-"+this.model.get("id"));
		this.delegateEvents()
		return this;
	}
})

/* 
	The menu view on top
	holds all the navigation button
	renders a category button for each category present in the app
	
*/
var HeaderView = Backbone.View.extend({
	className:'header',
	events:{
		'click .help':'help',
		'click .exit':'lock',
		'click .locked':'lock',
		'touchstart .help':'help',
		'touchstart .exit':'lock',
		'touchstart .locked':'lock'
	},
	initialize:function(){
		_.bindAll(this,'render','navigate');
		app.bind("navigate",this.navigate);
		this.navigation;	
	},
	/* 
		if the logout button is clickt we lock the app
		TODO: do this with an event trigger to remove dependencie on mainview
	 */
	lock:function(){
		app.trigger("lock",false)
	
	},
	/* renders the menu buttons and the categorie buttons */
	render:function(){
		$(this.el).html(ich.header({user:app.user}));
		if(app.user){
		var self=this
		_(app.categoryCollection.models).each(function(category){
			$(".categories",self.el).append(new CategoryBtn({model:category}).render().el)
		})
		}else{
			$(".categories",this.el).html();
		}
		this.navigate(this.navigation)
		this.delegateEvents()
		return this;
	},
	/* 
		updates the buttons to indicate the correct view / category
		the navigation object is saved to its persent in case the header redraws
	*/
	navigate:function(navigation){
		if(!navigation){return}
		this.navigation=navigation;
		var page=navigation.page;
		var category_id=navigation.category_id;
		if(page){
			$(".button",this.el).removeClass('active');
			if(page=="help"){$('.help',this.el).addClass('active')}
			if(page=="locked"){$('.locked',this.el).addClass('active')}
			if(category_id){
				$('.category-'+category_id,this.el).addClass('active')	
			}
		}
	},
	/* navigate to the help section */
	help:function(){app.routers.mainrouter.navigate('help',true)}
})

/* 
	Lockview is shown when no user is logged in 
	Has the keypad for the user to enter a pin
	Triggers an enterpin event
	The main view does the actuall validation and fires lock / unlock for the app

*/

var LockView = Backbone.View.extend({
	className:'lock',
	events:{
		"click .num-key":"keyInput",
		"touchstart .num-key":"keyInput",
		"click .clear-key":"clear",	
		"touchstart .clear-key":"clear",
		"click .enter-key":"enter",
		"touchstart .enter-key":"enter"		
	},
	initialize:function(){
		_.bindAll(this,"render","keyInput","enterPin","clear")
		this.defaultVal="Enter pin"	
		this.enterPin("1234")
	},
	/*
		handle click / tabs events on the keypad 
	   	checks if the pin is 4 digits long
	   	if so it triggers an enter pin event with the current pin	
	*/
	keyInput:function(event){
		
		var key=$(event.currentTarget).html();
		var pin=$('.pin-input',this.el).html();
		if(pin==this.defaultVal){pin="";}
		$('.pin-input',this.el).html(pin=pin+key)
		if(pin.length==4){
			this.enterPin(pin)
		}
		event.preventDefault();
	},
	/* reset the pin input */
	clear:function(){
		$('.pin-input',this.el).html(this.defaultVal)
	},
	/* lets the user manually send the pin */
	enter:function(){
		this.enterPin($('.pin-input',this.el).html())
	},
	/* 
		triggers the enterpin event
		the main view should listen for that
	 */
	enterPin:function(pin){
		this.trigger("enterpin",pin)
	},
	/* renders the keypad */
	render:function(){
		$(this.el).html(ich.lock());
		this.clear();
		this.delegateEvents()
		return this;
	}
})

/* 

	Main view handles login / logout and is the container for all views 


*/

var MainView = Backbone.View.extend({
	view:false,	
	initialize:function(){
		_.bindAll(this,'render','setView','lock','unlock','getUser','setUser','setConsumptions')
		this.lockView=new LockView();
		app.views.lockview=this.lockView;
		this.lockView.bind("enterpin",this.getUser)
		app.bind("lock",this.lock)
		var self=this;
		app.bind("navigate",function(){self.setTimer()})
		
	},
	/*  
		renders the whole app
		if the app is locked we show the lockscreen no matter what
		if the app is not locked and we have a current view we show it
		also resets the timer til auto lock after inactivity
	*/
	render:function(){
		this.el.html("");
		this.el.append(app.views.headerview.render().el)
		if(this.isLocked && this.view!==app.views.helpview){
			this.el.append(this.lockView.render().el)
		}else{
			if(this.view){
				this.el.append(this.view.render().el)
			}
		}
		this.setTimer();
	},
	/* 
		this method gets called when an enter pin event is triggerd
		it takes the pin, sends it to the server 
		the response is passed onto setUser 
		
	 */
	getUser:function(pin){
		var self=this;
		$.ajax({
			url: app.root+"api/userbypin",
			data:{pin:pin},
  			context: document.body,
  			success: function(response){
  				self.setUser(response)
  			}
  		})
	},
	/* 
		this method checks the response status
		if its not 200 the pin is not valid
		if its 200 the pin is valid and we have a user object to set
		we call the method to calculate the consumptions of the user
		and then unlock the app
	*/
	setUser:function(response){
		if(response.status!=200){return;}
		this.user=new UserModel(response.data.user);
		app.user=this.user
		this.setConsumptions(response.data.consumptions)
		this.unlock();
	},
	/*  
		first we reset all the consumption counts 
		then we loop through the consumptions 
		and increase the counters on the item models
		lastly we fire change events on the items we modified
	*/
	setConsumptions:function(consumptions){
		_(app.itemCollection.models).each(function(item){
			item.reset();	
		},this)
		var item;
		_(consumptions).each(function(consumption){
			item=app.itemCollection.get(consumption.item_id)
			if(item){
				item.countUp(true)
			}
		},this)
		_(app.itemCollection.models).each(function(item){
			if(item.hasChanged()){
			item.change()
			}
		})
	},
	/* 
		sets the app in a locked state
		resets the user object
		and triggers a global lock event
	*/
	lock:function(){
		this.isLocked=true;
		this.user=false;
		app.user=false;
		app.routers.mainrouter.navigate('locked',true)
		
	},
	/* 
		sets the app in an unlocked state
		triggers a global unlock event with current user object as a parameter	
		renders the view and sets the auto lock timer
	*/
	unlock:function(){
		this.isLocked=false;
		app.trigger("unlock",this.user)
		this.render()
		this.setTimer()
	},
	/* 
		this sets a timer to auto lock the app after some time of inactivity
	*/
	setTimer:function(millis){
		if(this.timerId){clearTimeout(this.timerId)}
		millis= millis || 60000;
		var self=this
		this.timerId=setTimeout(function(){app.trigger("lock")},millis);
	},
	/*
		this method sets the current view to be shown and calls render
	*/
	setView:function(view){
		if(this.view!=view){
		this.view=view;
		this.render()
		}
	}
})

/* 

	Main app router
	fires a navigation changed event that updates the state of the menu
	not sure if thats the cleanest possible solution
*/
var MainRouter = Backbone.Router.extend({
	routes: {
    "help":"help",
    "bar":"bar",
    "bar/:category": "bar",
    "locked":"locked",
  	},
  	/*
  		b
  	*/
  	initialize: function(options) {
  		_.bindAll(this,"bar","help","home")
  		app.bind("lock",this.locked);
  		app.bind("unlock",this.home);
  		$(window).resize(function() {
  			app.trigger("resize");
  		})
  	},
  	/*
  		 if the app is locked we show the lock view
  	*/
  	locked:function(){
  		app.views.mainview.setView(app.views.lockview)
  		app.trigger("navigate",{page:"locked",category:false})
  	},
  	/*
  		navigates to the first category in the collection
  	*/
  	home:function(){
  		this.navigate('bar/'+app.categoryCollection.models[0].get("id"),true)
  	},
  	/* 
  		sets the bar view to be shown in the main view
  		navigates to a category or to the first category if no id is provided
  	*/
  	bar:function(category_id){
  		app.views.mainview.setView(app.views.barview)
  		if(category_id){
  			app.views.barview.openCategory(category_id)
  			app.trigger("navigate",{page:"bar",category_id:category_id})
  		}else{
  			this.home();
  		}
  	},
  	/*
  		sets the help view to be shown in the main view
  	*/
  	help:function(){
  		app.views.mainview.setView(app.views.helpview)
  		app.trigger("navigate",{page:"help",category:false})
  	},
  	
  	
})

	// basic app setup
	window.app=_.extend({}, Backbone.Events);
	app.root="/bar/public/"
	app.hasTouch="ontouchstart" in document.documentElement;
	app.views={}
	app.routers={}
	
	// main init method
	app.initialize=function(){
		/* 
			init serverside generated collections
			save some ajax requests
		 	items and categories
		 */
		 
		if(window.initItems){
			app.itemCollection=new ItemCollection(initItems);
		}else{
			app.itemCollection=new ItemCollection();
		}
	
		if(window.initCategories){
			app.categoryCollection=new CategoryCollection(_.union([{"id":"0","label":"Favorites","order":"0","ignore":"0", "favorit":true}],initCategories));
			
		}else{
			app.categoryCollection=new CategoryCollection();
		}
		
		/* get the views ready */
		app.views.barview=new BarView();
		app.views.trayview=new TrayView();	
		app.views.helpview=new HelpView();
		app.views.headerview=new HeaderView();
		app.views.mainview=new MainView({el:$("#app")});
		
		/* set the main router */
		app.routers.mainrouter=new MainRouter();
		/* lock the app */
		app.trigger('lock')
		//app.views.lockview.enterPin("1234");
	}
	/* 
		call the init method and start the backbone history
		push state is a bit broken because the app auto navigates to the lockscreen	
	*/	
	app.initialize();
	//{pushState: true,root: app.root}
	Backbone.history.start()
	
	/* 
		fix so touch devices ignores clicks
		on iOS they have a 300ms delay, makes the UI unsexy
	   	we use touchend events instead of click events
	   	good read on touch detection http://modernizr.github.com/Modernizr/touch.html
	   	I have no Idea why the touchstart listener is necessary, but without it touchend events do not fire	
	 */
	if(app.hasTouch){
		document.addEventListener("touchstart",function(e){},true);
		document.addEventListener("click",function(e){e.preventDefault();e.stopPropagation();}, true);
	}
	
function Swipe(el) {
	// source: http://mscerts.programming4.us/programming/coding%20javascript%20for%20mobile%20browsers%20(part%2012)%20-%20swipe%20gesture.aspx
    // Constants
   
    
    this.HORIZONTAL     = 1;
    this.VERTICAL       = 2;
    this.AXIS_THRESHOLD = 50;  // The user will not define a perfect line
    this.GESTURE_DELTA  = 120;  // The min delta in the axis to fire the gesture

    // Public members
    this.direction    = this.HORIZONTAL;
    this.element      = el
    this.onswiperight = null;
    this.onswipeleft  = null;
    this.onswipeup    = null;
    this.onswipedown  = null;
    this.onswipemove  = null;
    this.inGesture    = false;
	this.gestureFired =false;
    // Private members
    this._originalX = 0
    this._originalY = 0
    var _this = this;
    // Makes the element clickable on iPhone
    this.element.onclick = function() {void(0)};
	
    var mousedown = function(event) {
         // Finger press
         event.preventDefault();
         _this.gestureFired =false;
         _this.inGesture  = true;
         _this._originalX = (event.touches) ? event.touches[0].pageX : event.pageX;
         _this._originalY = (event.touches) ? event.touches[0].pageY : event.pageY;
         // Only for iPhone
         if (event.touches && event.touches.length!=1) {
             _this.inGesture = false; // Cancel gesture on multiple touch
         }
    };

    var mousemove = function(event) {
         // Finger moving
         event.preventDefault();
         var delta = 0;
         // Get coordinates using iPhone or standard technique
         var currentX = (event.touches) ? event.touches[0].pageX : event.pageX;
         var currentY = (event.touches) ? event.touches[0].pageY : event.pageY;

         // Check if the user is still in line with the axis
         if (_this.inGesture) {
             if ((_this.direction==_this.HORIZONTAL)) {
                 delta = Math.abs(currentY-_this._originalY);
             } else {
                 delta = Math.abs(currentX-_this._originalX);
             }
             if (delta >_this.AXIS_THRESHOLD) {
                 // Cancel the gesture, the user is moving in the other axis
                 _this.inGesture = false;
             }
         }

         // Check if we can consider it a swipe
         if (_this.inGesture) {
             if (_this.direction==_this.HORIZONTAL) {
                 delta = Math.abs(currentX-_this._originalX);
                 if (currentX>_this._originalX) {
                    direction = 0;
                 } else {
                    direction = 1;
                 }
             } else {
                 delta = Math.abs(currentY-_this._originalY);
                 if (currentY>_this._originalY) {
                    direction = 2;
                 } else {
                    direction = 3;
                 }
             }
             if(_this.onswipemove){
			 	_this.onswipemove(delta-direction*2*delta)	
			 }
             if (delta >= _this.GESTURE_DELTA) {
                 // Gesture detected!
                 var handler = null;
                 switch(direction) {
                      case 0: handler = _this.onswiperight; break;
                      case 1: handler = _this.onswipeleft; break;
                      case 2: handler = _this.onswipedown; break;
                      case 3: handler = _this.onswipeup; break;
                 }
                 if (handler!=null) {
                     // Call to the callback with the optional delta
                     handler(delta);
                 }
                 
                 _this.gestureFired=true;
                 
                 _this.inGesture = false;
             }else{
             
             }

         }
    };
	var mouseup=function(event){
		if(_this.gestureFired){
			event.preventDefault();
			event.stopPropagation();
		}
		if(_this.onswipeend){
			_this.onswipeend();
		}
	}

    // iPhone and Android's events
    this.element.addEventListener('touchstart', mousedown, false);
    this.element.addEventListener('touchmove', mousemove, false);
    this.element.addEventListener('touchend',mouseup,true);
    this.element.addEventListener('touchcancel', function() {
        _this.inGesture = false;
    }, false);

    // We should also assign our mousedown and mousemove functions to
    // standard events on compatible devices
}

	
	
	
	
})