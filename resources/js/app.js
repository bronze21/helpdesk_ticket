import './bootstrap';
import ImagePreview from './imagePreview';

import Alpine from 'alpinejs';
import * as bootstrap from 'bootstrap';
import '@popperjs/core';
import Swal from 'sweetalert2';
import tinymce from 'tinymce';
import 'tinymce/icons/default';
import 'tinymce/models/dom';
import 'tinymce/themes/silver';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/table';
import 'tinymce/plugins/help';
import 'tinymce/plugins/wordcount';
import 'tinymce/skins/ui/oxide/skin.js';
import 'tinymce/skins/ui/oxide/content.js';
import 'tinymce/skins/content/default/content.js';

import DataTable from 'datatables.net-dt';
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-colreorder-bs5';
import 'datatables.net-responsive-bs5';
import 'jszip';
import 'pdfmake';
import flatpickr from 'flatpickr';
import moment from 'moment';
import { Indonesian } from 'flatpickr/dist/l10n/id';

const appEl = document.getElementById('app')
const activePlugin = JSON.parse(appEl.dataset.plugin) || []
window.Tinymce = tinymce;
window.bootstrap = bootstrap;
window.FlatPickr = flatpickr
window.moment = moment

if(activePlugin.includes('DataTables')){
	window.DataTables = DataTable;
}
if(activePlugin.includes('Swal2')){
	window.Swal2 = Swal;
	window.SwalBS = Swal.mixin({
		customClass: {
			container: 'swalbs5',
			confirmButton: 'btn btn-primary',
			cancelButton: 'btn btn-secondary',
			denyButton: 'btn btn-outline-danger',
			title: 'text-primary',
			input: 'form-control',
			image: 'img-thumbnail',
			validationMessage: 'invalid-feedback',
			loader: 'm-0',
			actions: 'w-100'
		},
		buttonsStyling: false,
		showCloseButton: true,
		didOpen: () => {
			// Tambahkan kelas 'show' ke tombol
			const confirmButton = Swal.getConfirmButton();
			const cancelButton = Swal.getCancelButton();
			const denyButton = Swal.getDenyButton();
			
			if (confirmButton && confirmButton.style.display !== 'none') confirmButton.classList.add('show');
			if (cancelButton && cancelButton.style.display !== 'none') cancelButton.classList.add('show');
			if (denyButton && denyButton.style.display !== 'none') denyButton.classList.add('show');
		},
	});
}
window.ImagePreview = ImagePreview
window.Alpine = Alpine;
Alpine.start();

console.log('imagePreview',window.ImagePreview)

document.addEventListener('DOMContentLoaded', () => {
	moment.locale('id')
	const toastElList = document.querySelectorAll('.toast') // Ganti ID jika berbeda
	const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl, {
		autohide: true,
		delay: 2000, // 2 detik
	}))
	const mainForms = document.querySelectorAll('main form');
	mainForms.forEach(form => {
		form.addEventListener('submit', function(event) {
			const submitButton = form.querySelector('button[type="submit"]');
			if (submitButton) {
				submitButton.innerText = 'Loading...';
				submitButton.disabled = true;
			}
		});
	});
	const _DatePickers = document.querySelectorAll('.input-dates')
	_DatePickers.forEach(picker => {
		let data 	= picker.dataset
		let opt		= {
			locale		: Indonesian,
			mode		: data?.mode			 || 'sigle',
			dateFormat	: data?.date_format 	 || "Y-m-d",
			enableTime	: data?.enable_time=='1' || false,
			time_24hr	: data?.time_24hr=='1'   || false,
			altInput	: data?.alt_input=='1'   || false,
			altFormat	: data?.alt_format  	 || "D, j M Y",
		}
		console.log('el data',data,opt)
		flatpickr(picker, opt)
	})
});
