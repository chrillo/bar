var ConsumptionModel = Backbone.Model.extend({})
var ConsumptionCollection = Backbone.Collection.extend({model:ConsumptionModel})
var ConsumptionView = Backbone.View.extend({
	className:'consumption',
	initialize:function(){
		_.bindAll(this,"render","removeItem","addItem","setItems")
		this.items=[]
	},
	render:function(){
		
		var price=0;
		if(this.items.length){
		_(this.items).each(function(item){
				price+=item.get("price")*1;
		},this)
		var data={price:price,title:this.items[0].get("title"),count:this.items.length}
		$(this.el).html(ich.consumption(data))
		}
	
		
		return this;
	},
	addItem:function(item){
		this.items.push(item)
		this.render();
	}
	,
	setItems:function(items){
		this.items=items;
		this.render();	
	},
	removeItem:function(){
		
	}
})

var AccountView = Backbone.View.extend({
	initialize:function(){
		_.bindAll(this,'render',"load")

		this.collection=new ConsumptionCollection();
		this.collection.bind("add",this.render)
		this.collection.bind("change",this.render)
		this.collection.bind("reset",this.render)
		
		this.collection.url="api/consumptions"
		
	},
	load:function(){
		console.log(app.views.mainview.user)
		if(app.views.mainview.user){
			this.collection.fetch({data:{pin:app.views.mainview.user.get("pin")}})
		}
	},
	render:function(){
		var items={};
		console.log("render consumptions",this.collection)
		$(this.el).html("");
		var price=0;
		var count=0;
		_(this.collection.models).each(function(consumption){
			price+=consumption.get("price")*1;
			count++;
			if(!items[consumption.get("item_id")]){
				items[consumption.get("item_id")]=new ConsumptionView()
				$(this.el).append(items[consumption.get("item_id")].render().el)
			}
			items[consumption.get("item_id")].addItem(consumption)
		},this)
		price=Math.floor(price*100)/100
		$(this.el).append(ich.consumption({title:"gesamt",price:price,count:count}))
		$(this.el).html();
		return this;
	}
})
