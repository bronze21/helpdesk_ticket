<div class="row">
	<div class="col-12">
		<div class="card mb-5">
			<div class="card-body">
				<h5 class="card-title">Welcome Back, {{auth()->user()->name}}</h5>
				<div class="w-100 py-2"></div>
				<div class="row">
					<div class="col-lg-3 col-6">
						<div class="card">
							<div class="card-body">
								<span class="d-block">Title</span>
								<div class="w-100 py-3"></div>
								<h3 class="text-end fw-bold mb-0">Value</h3>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-6">
						<div class="card">
							<div class="card-body">
								<span class="d-block">Title</span>
								<div class="w-100 py-3"></div>
								<h3 class="text-end fw-bold mb-0">Value</h3>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-6">
						<div class="card">
							<div class="card-body">
								<span class="d-block">Title</span>
								<div class="w-100 py-3"></div>
								<h3 class="text-end fw-bold mb-0">Value</h3>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-6">
						<div class="card">
							<div class="card-body">
								<span class="d-block">Title</span>
								<div class="w-100 py-3"></div>
								<h3 class="text-end fw-bold mb-0">Value</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
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