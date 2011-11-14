<h2 class="first">Editing Consumption</h2>

<?php echo render('consumptions/_form'); ?>
<br />
<p>
<?php echo Html::anchor('consumptions/view/'.$consumption->id, 'View'); ?> |
<?php echo Html::anchor('consumptions', 'Back'); ?></p>
