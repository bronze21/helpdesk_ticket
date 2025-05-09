{{-- <div class="py-3 w-100"></div> --}}
<h4 class="card-title mb-2">Quick Shortcuts</h4>
<div class="row mx-0 quick-shortcuts" style="gap:10px;">
	<div class="col bg-white border rounded p-3 border-dark">
		<span class="d-block fw-bold">Ticket</span>
		<div class="w-100 py-3"></div>
		<h5 class="text-end mb-0 value">Create New Ticket</h5>
	</div>
	<div class="col bg-white border rounded p-3 border-dark">
		<span class="d-block fw-bold">Ticket</span>
		<div class="w-100 py-3"></div>
		<h5 class="text-end mb-0 value">History</h5>
	</div>
	<div class="col bg-white border rounded p-3 border-dark">
		<span class="d-block fw-bold">Ticket</span>
		<div class="w-100 py-3"></div>
		<h5 class="text-end mb-0 value">Search</h5>
	</div>
	<div class="col bg-white border rounded p-3 border-dark">
		<span class="d-block fw-bold">User</span>
		<div class="w-100 py-3"></div>
		<h5 class="text-end mb-0 value">List User</h5>
	</div>
</div>
<div class="w-100 py-3"></div>
<div class="row" id="tables">
	<div class="col-12">
		<div class="card bg-white">
			<div class="card-body">
				<h4 class="card-title">Latest Ticket</h4>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">No.</th>
								<th scope="col">Kode Tiket</th>
								<th scope="col">Divisi</th>
								<th scope="col">Subjek</th>
								<th scope="col" class="text-end">Terakhir di ubah</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($datas as $ticket)
							<tr>
								<td class="align-middle">{{$loop->iteration}}</td>
								<td class="align-middle">{{$ticket->code}}</td>
								<td class="align-middle">{{$ticket->category->name}}</td>
								<td class="align-middle">{{$ticket->title}}</td>
								<td class="text-end align-middle">{{$ticket->latest_update->diffForHumans()}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					{{$datas->links()}}
				</div>
			</div>
		</div>
	</div>
</div>