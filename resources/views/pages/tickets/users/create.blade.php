@extends('layouts.dashboard',['plugins'=>['Swal2','Tinymce']])

@section('submenu')
	
@endsection

@section('content')
<section class="container">
	<form action="{{route('tickets.store')}}" method="post" enctype="multipart/form-data" class="main-form position-relative">
		@csrf
		<div class="card mb-3" id="step-1">
			<div class="card-body">
				<h5>Pilih kategori tiket <small class="text-danger">*</small></h5>
				<div class="form-group row mx-0" style="gap: 1rem; flex-wrap: nowrap; flex-direction: row; overflow-x: auto;">
					@foreach ($categories as $category)
					<div class="col px-0">
						<button type="button" class="btn btn-outline-primary w-100 position-relative z-2" :class="{ 'active': required.category == '{{ $category->slug }}' }" x-on:click="setCategory('{{ $category->slug }}')">
							<span class="z-1">{{ $category->name }}</span>
						</button>
					</div>
					@endforeach
					<input type="hidden" name="category" x-model="required.category">
				</div>
			</div>
		</div>
		<div class="card" id="step-2">
			<div class="card-body" 
				:class="{ 'show': !required.category }" x-show="!required.category" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="transition: opacity .15s ease-in-out;"
			>
				<h4 class="text-center my-5">Mohon Pilih Kategori Terlebih Dahulu</h4>
			</div>
			<div class="card-body" id="step-2" 
				x-cloak x-show="required.category" x-transition:enter-end="opacity-100" x-transition:enter-start="opacity-0" x-transition:delay.1550ms style="transition: opacity .75s ease-in-out;"
			>
				<h5>Harap isi form dibawah ini.</h5>
				<div class="form-group mb-3 row">
					<input type="hidden" name="ticket_code" value="{{$ticket_id}}">
					<label for="title" class="col-md-3 align-self-center">Judul: <small class="text-danger">*</small></label>
					<div class="col">
						<input type="text" name="title" id="title" class="form-control" required="required" x-model="required.title">
					</div>
				</div>
				<div class="form-group mb-3 row" x-show="showSubCategory">
					<label for="subcategory" class="col-md-3 align-self-center">Layanan Terkait: <small class="text-danger">*</small></label>
					<div class="col col-md-4">
						<select name="subcategory" id="subcategory" class="form-control" x-model="required.subcategory">
							<option value="">Pilih Layanan Terkait</option>
							<template x-for="subcategory in subCategories" :key="subcategory.id">
								<option :value="subcategory.slug" x-text="subcategory.name"></option>
							</template>
						</select>
					</div>
				</div>
				<div class="form-group mb-3 row" x-show="showSubCategory">
					<label for="priority" class="col-md-3 align-self-center">Prioritas: <small class="text-danger">*</small></label>
					<div class="col col-md-4">
						<select name="priority" id="priority" class="form-control" x-model="required.priority">
							<option value="">Pilih Tingkat Prioritas</option>
							@foreach ($priorities as $priority)
							<option value="{{$priority}}">{{ucwords($priority)}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group mb-3">
					<label for="message">Pesan: <small class="text-danger">*</small></label>
					<div class="float-end">
						<a href="#" role="button" class="link" x-on:click="addDummyText()">Input Dummy Text</a>
					</div>
					<textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Please Input Your Message"></textarea>
				</div>
				<div class="form-group">
					<label for="attachments">Attachments: </label>
					<div id="image-preview" class="form-control">
						<input type="file" multiple id="file-input">
						<div class="preview-container"></div>
					</div>
				</div>
			</div>
			<div class="card-body text-end" 
				x-cloak x-show="required.category" x-transition:enter-end="opacity-100" x-transition:enter-start="opacity-0" x-transition:delay.1550ms style="transition: opacity .75s ease-in-out;"
			>
				<button type="submit" class="btn btn-primary" x-show="required.category">Submit</button>
			</div>
		</div>
	</form>
</section>
@endsection

@section('js')
@php
	// $data_raw = $categories->map(fn($category) => (object)['name' => $category->name, 'slug' => $category->slug, 'subcategory'=>$category->subCategories->select('id','name','slug')]);
	$data_raw = $categories->mapWithKeys(function($a,$b){
		return [
			$a->slug => [
				'name' => $a->name,
				'subcategory' => $a->subCategories->select('id','name','slug')
			]
		];
	});
@endphp
<script>
	document.addEventListener('alpine:init', () => {
		console.log("APLINE INIT SUCCESS")
		let textEditor;
		Alpine.data('ticket', () => ({
			datas: @json($data_raw),
			subCategories: [],
			required: {
				title: '',
				category: '',
				subcategory: '',
				priority: '',
			},
			showSubCategory: false,
			init() {
				this.$watch('subCategories',()=>{
					console.log('subCategories',this.subCategories)
				})
				console.log(this.datas)
			},
			setCategory(category){
				this.required.category = category
				this.showSubCategory = this.datas[category]?.subcategory.length > 0
				this.subCategories = this.datas[category]?.subcategory
				console.log('subcategory',this.datas[category]?.subcategory.length)
			},
			addDummyText(){
				let message = `
				<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Fugiat error autem delectus quas earum similique animi velit voluptatem, voluptate a necessitatibus cum dolores aliquam nostrum ipsam aliquid nam reprehenderit inventore. Quo harum sit, sed asperiores ipsam odio quod et magnam facere aliquid hic consequuntur enim sint perspiciatis nulla nihil animi fuga fugit saepe tempore ab nesciunt. Perspiciatis molestiae perferendis beatae! Ad sed unde sint dolor exercitationem amet! Quod quo consequatur assumenda voluptas. Inventore cumque debitis mollitia pariatur ipsam temporibus praesentium facilis delectus? Incidunt aspernatur fugit quis! Nam eaque vero fugit? Consequuntur ea, sequi eveniet cum perspiciatis sapiente hic cumque aliquid quibusdam. Praesentium, cum recusandae alias expedita perferendis cupiditate aut commodi, delectus, voluptas optio facere labore. Minima explicabo incidunt sunt alias!</p>

				<p>Corporis temporibus labore vel optio delectus praesentium facere dignissimos ut saepe quisquam rem illo reprehenderit culpa, aliquid dolores itaque quas aliquam possimus doloremque quo odio autem unde numquam obcaecati! Corporis.
				Consequatur ad quo quas odio eaque quos sed odit libero voluptates neque velit fugiat iure ipsam, hic asperiores repudiandae? Sunt ad at cumque debitis sint aperiam nam minima explicabo quas.</p>

				<p>Laudantium assumenda officiis recusandae adipisci aspernatur corporis laboriosam aut optio, quaerat voluptates ratione sunt mollitia numquam possimus similique ipsum cum doloribus autem doloremque, iure qui! Architecto iusto inventore doloribus sapiente. Pariatur, rerum aliquam! Debitis commodi iure neque dolores nostrum quibusdam quidem saepe ipsum dolore dicta, iste cumque rem ab atque cum laudantium dolorum fugit consequuntur nisi, aut pariatur architecto explicabo. Dicta error molestiae qui velit eos provident in ratione quasi necessitatibus voluptate! Impedit libero nisi consequatur aliquid, praesentium suscipit ducimus architecto, quae itaque repudiandae sunt corporis sint sed adipisci amet.</p>

				<p>Minima nulla id impedit accusantium. Quod dolorem rem sapiente qui repellendus quibusdam soluta itaque voluptatum doloremque, aperiam, deserunt sunt rerum debitis nam. Doloribus vel quis quia ducimus vitae excepturi sed!</p>
				`
				let editor = Tinymce.get(`message`)
				console.log(editor)
				editor.insertContent(message)
			}
		}))
		let masterContent = document.querySelector('.master-content')
		masterContent.setAttribute('x-data', 'ticket')
		textEditor = Tinymce.init({
			license_key: 'gpl',
			selector: '#message',
			base_url: '/tinymce',
			skin: false,
			height: 600,
			menubar: false,
			skin_url: 'default',
    		content_css: 'default',
			plugins: [
				'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
				'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
				'insertdatetime', 'media', 'table', 'help', 'wordcount'
			],
			toolbar: 'undo redo | blocks | ' +
			'fontsize | bold italic underline backcolor | ' +
			'table | alignleft aligncenter ' +
			'alignright alignjustify | bullist numlist outdent indent | ' +
			'removeformat | help',
			content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
		});

		// let Attachments = FilePond.create(document.querySelector('.filepond'),{
		// 	multiple: true,
		// 	name: 'attachments',
		// 	class: 'form-control',
		// 	allowImagePreview: true,
		// 	acceptedFileTypes: ['image/*'],
		// 	imagePreviewHeight: 170, // Atur tinggi preview untuk gambar
    	// 	imageCropAspectRatio: '1:1',
		// });
		let fileUpload = ImagePreview.init('#image-preview', {
			imageClass: 'img-thumbnail'
		});
	})

	document.addEventListener('DOMContentLoaded', () => {
	})
</script>
@endsection