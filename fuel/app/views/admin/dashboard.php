<div class="">
	<h2>Sales for the last <?php echo $days ?> days</h2>
	<div id="saleschart" style="height:240px;width:100%;"></div>
	<div>
		<h3>Total: <?php echo  $total ?>€ /<?php echo $totalCount; ?> </h3>
	</div>
</div>
<div class="">
	<h2>Users</h2>
	<h3>Total: <?php echo $users; ?></h3>
</div>
<?php echo Asset::js('raphael.min.js'); ?>
<?php echo Asset::js('g.raphael.min.js'); ?>
<?php echo Asset::js('g.pie.js'); ?>
<?php echo Asset::js('g.bar.min.js'); ?>
<script type="text/javascript">
	$(document).ready(function(){
		var r = Raphael("saleschart"),
		fin = function () {
                        this.flag = r.popup(this.bar.x, this.bar.y, this.bar.value || "0").insertBefore(this);
                    },
                    fout = function () {
                        this.flag.animate({opacity: 0}, 300, function () {this.remove();});
                    }
		
	// r.barchart(10, 30, 200,100 , [[], []], {stacked: true}).hover(fin, fout);	
		
     pie = r.piechart(400, 80, 50, [<?php echo $paid ?>, <?php echo $unpaid ?>], { legend: ['##€ paid','##€ unpaid'], legendpos: "west", href: []});
	 pie.hover(function () {
                    this.sector.stop();
                    this.sector.scale(1.1, 1.1, this.cx, this.cy);

                    if (this.label) {
                        this.label[0].stop();
                        this.label[0].attr({ r: 7.5 });
                        this.label[1].attr({ "font-weight": 800 });
                    }
                }, function () {
                    this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "bounce");

                    if (this.label) {
                        this.label[0].animate({ r: 5 }, 500, "bounce");
                        this.label[1].attr({ "font-weight": 400 });
                    }
                });	
		
		
	})
</script>