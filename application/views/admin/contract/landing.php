<ul class="breadcrumb">
	<li class="active">All Contracts</li>
</ul>  

<?php if(isset($contracts)) {?>
		
		<table class="table">
			<tr>
				<th>Number</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Carrier Name</th>
				<th></th>
			</tr>
			<?php foreach($contracts as $row): ?>
			<tr>
				<td><a href="<?php echo site_url().'/admin/contract/manage/'.$row->id ?>"><?php echo $row->number; ?></a></td>
				<td><?php echo date('F d, Y', strtotime($row->start_date)); ?></td>
				<td><?php echo date('F d, Y', strtotime($row->end_date)); ?></td>
				<td><?php echo $row->carrier_name; ?></td>				
			</tr>
			<?php endforeach; ?>
		</table>
<?php } ?>

<a href="<?php echo site_url().'/admin/contract/add/'.$customer->id ?>" class="btn btn-primary">New Contract</a>
