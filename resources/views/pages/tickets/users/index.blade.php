{{--
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
--}}

@extends('layouts.dashboard',['plugins'=>['DataTables','Swal2']])

@section('submenu')
@section('submenu')
<div class="links h-100">
	<ul class="nav h-100">
		<li class="nav-item">
			<button role="button" class="nav-link text-dark" x-on:click="filter.isShow = !filter.isShow">
				<i class="fa fa-filter d-block text-center" style="font-size: 1.25rem"></i>
				<small>Filter</small>
			</button>
		</li>
		<li class="nav-item">
			<a href="{{route('tickets.create')}}" class="nav-link text-dark">
				<i class="fa fa-plus d-block text-center" style="font-size: 1.25rem"></i>
				<small>Create new</small>
			</a>
		</li>
	</ul>
</div>
@endsection
@endsection

@section('content')
<section class="container-fluid h-100 p-3">
	<div class="row mb-3" x-show="filter.isShow" x-transition:enter="fade show" x-transition:leave="fade hide">
		<form method="GET" action="" class="col-12">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"><i class="fa fa-filter me-1"></i>Filter</h5>
					<div class="row">
						<div class="col-md-6 mb-3 mb-lg-0">
							<div class="row h-100">
								<div class="col-12 mb-3 mb-lg-0">
									<label for="search" class="">Search: </label>
									<input type="text" name="search" id="search" class="form-control">
								</div>
								<div class="col-12 mb-3 mb-lg-0">
									<label for="date_filter">Date</label>
									<input type="text" name="date_filter" id="date_filter" class="form-control input-dates" data-alt_input="1" data-mode="range">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row h-100">
								<div class="col-12 col-md-6 mb-3 mb-lg-0">
									<div class="row h-100">
										<div class="col-12 mb-3 mb-lg-0">
											<label for="priority">Priority</label>
											<select name="priority" id="priority" class="form-control">
												<option value="all" selected>Semua Prioritas</option>
												<option value="high">Tinggi</option>
												<option value="normal">Normal</option>
												<option value="low">Rendah</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6 mb-3 mb-lg-0">
									<div class="row h-100">
										<div class="col-12 mb-3 mb-lg-0">
											<label for="status">Status</label>
											<select name="status" id="status" class="form-control">
												<option value="all" selected>Semua Status</option>
												<option value="open" selected>Menunggu Respon</option>
												<option value="on_going">Dalam Proses</option>
												<option value="resolved">Telah Selesai</option>
												<option value="unresolved">Tidak Selesai</option>
												<option value="closed">Ditutup</option>
											</select>
										</div>
										<div class="col-12 align-self-end">
											<button type="submit" class="btn btn-primary opacity-75 w-100"><i class="fa fa-filter me-1"></i>Filter</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4>List Tickets</h4>
					<div class="row">
						<div class="col-12">
							<div class="table-responsive">
								<table class="table table-borderless datatables" id="tickets" data-ajax_url="{{$datas_url}}">
									<thead>
										<tr>
											<th>#</th>
											<th>Latest Update</th>
											<th>Department</th>
											<th>Topic</th>
											<th>Priority</th>
											<th>Status</th>
											<th class="text-end">Action</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('modal')
	
@endsection

@section('js')
<script>
	let DTable;
	function initDTable(){
		console.log('Init datatable');
		let el = document.querySelector('.datatables')
		let url = el.dataset?.ajax_url || false
		let opt = {
			ajax: url || false,
			processing: true,
			serverSide: true,
			columns: [
				{data: 'id', name: 'id', orderable: true, searchable: true},
				{data: 'latest_update', name: 'latest_update', orderable: true, searchable: true},
				{data: 'category.name', name: 'department', orderable: true, searchable: true},
				{data: 'topic', name: 'topic', orderable: true, searchable: true},
				{data: 'priority', name: 'priority', orderable: true, searchable: true},
				{data: 'status', name: 'status', orderable: true, searchable: true},
				{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
				{
					"targets": 6,
					"className": "text-end"
				}
			]
		}
		DTable = new DataTables('#tickets', opt)
	}
	document.addEventListener('alpine:init', () => {
		initDTable();
		console.log("APLINE INIT SUCCESS")
		Alpine.data('tickets', () => ({
			filter: {
				isShow: false
			},
			// init() {
			// },
		}));
		let masterContent = document.querySelector('.master-content')
		masterContent.setAttribute('x-data', 'tickets')
	})
</script>
@endsection