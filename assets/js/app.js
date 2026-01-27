$(function () {
	"use strict";

	// search bar
	$(".mobile-search-icon").on("click", function () {
		$(".search-bar").addClass("full-search-bar");
	});
	$(".search-close").on("click", function () {
		$(".search-bar").removeClass("full-search-bar");
	});
	$(".mobile-toggle-menu").on("click", function () {
		$(".wrapper").addClass("toggled");
	});
	// toggle menu button
	$(".toggle-icon").click(function () {
		if ($(".wrapper").hasClass("toggled")) {
			// unpin sidebar when hovered
			$(".wrapper").removeClass("toggled");
			$(".sidebar-wrapper").unbind("hover");
		} else {
			$(".wrapper").addClass("toggled");
			$(".sidebar-wrapper").hover(function () {
				$(".wrapper").addClass("sidebar-hovered");
			}, function () {
				$(".wrapper").removeClass("sidebar-hovered");
			})
		}
	});
	/* Back To Top */
	$(document).ready(function () {
		$(window).on("scroll", function () {
			if ($(this).scrollTop() > 300) {
				$('.back-to-top').fadeIn();
			} else {
				$('.back-to-top').fadeOut();
			}
		});
		$('.back-to-top').on("click", function () {
			$("html, body").animate({
				scrollTop: 0
			}, 600);
			return false;
		});
	});
	$(function () {
		for (var i = window.location, o = $(".metismenu li a").filter(function () {
			return this.href == i;
		}).addClass("").parent().addClass(""); ;) {
			if (!o.is("li")) break;
			o = o.parent("").addClass("").parent("").addClass("");
		}
	}),
		// metismenu
		$(function () {
			$('#menu').metisMenu();
		});
	// chat toggle
	$(".chat-toggle-btn").on("click", function () {
		$(".chat-wrapper").toggleClass("chat-toggled");
	});
	$(".chat-toggle-btn-mobile").on("click", function () {
		$(".chat-wrapper").removeClass("chat-toggled");
	});
	// email toggle
	$(".email-toggle-btn").on("click", function () {
		$(".email-wrapper").toggleClass("email-toggled");
	});
	$(".email-toggle-btn-mobile").on("click", function () {
		$(".email-wrapper").removeClass("email-toggled");
	});
	// compose mail
	$(".compose-mail-btn").on("click", function () {
		$(".compose-mail-popup").show();
	});
	$(".compose-mail-close").on("click", function () {
		$(".compose-mail-popup").hide();
	});

	$(document).on('submit', '#formPenilaian', function (e) {
		e.preventDefault();
		let form = this;
		let formData = new FormData(form);

		$.ajax({
			url: 'simpan_penilaian',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function (res) {
				if (res.success) {
					$('#modalPenilaian').modal('hide');
					success_noti(res.message);
				} else {
					error_noti(res.message);
				}
			},
			error: function () {
				warning_noti('Terjadi kesalahan saat menyimpan data.');
			}
		});
	});

	const descriptions = {
		1: "Tidak Memuaskan",
		2: "Kurang Memuaskan",
		3: "Cukup Memuaskan",
		4: "Memuaskan",
		5: "Sangat Memuaskan"
	};

	document.querySelectorAll('input[name="ramah"]').forEach((input) => {
		input.addEventListener("change", function () {
			const value = parseInt(this.value);
			document.getElementById("ramah-description").textContent = descriptions[value];
		});
	});

	document.querySelectorAll('input[name="puas"]').forEach((input) => {
		input.addEventListener("change", function () {
			const value = parseInt(this.value);
			document.getElementById("puas-description").textContent = descriptions[value];
		});
	});
});

function warning_noti(pesan) {
	Lobibox.notify('warning', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-error',
		msg: pesan
	});
}

function error_noti(pesan) {
	Lobibox.notify('error', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-x-circle',
		msg: pesan
	});
}

function success_noti(pesan) {
	Lobibox.notify('success', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-check-circle',
		msg: pesan
	});
}

function BukaModalPenilaian(id) {
	document.querySelectorAll('input[name="ramah"]').forEach((input) => {
		input.checked = false;
	});
	document.querySelectorAll('input[name="puas"]').forEach((input) => {
		input.checked = false;
	});

	// Hapus deskripsi rating
	document.getElementById("ramah-description").textContent = "";
	document.getElementById("puas-description").textContent = "";

	$.post('v_modal_petugas', {
		id: id
	}, function (response) {
		var json = jQuery.parseJSON(response);
		if (json.st == 1) {
			$("#title").html("");
			$("#id_").val('');
			$("#nama_").html('');
			$('#posisi_').html('');

			$("#title").append(json.judul);
			$("#id_").val(json.id);
			$("#nama_").append(json.nama);
			$('#posisi_').append(json.posisi);

			const preview = document.getElementById('foto');
			if (json.foto) {
				preview.src = json.foto;
				preview.style.width = '50%'; // Gunakan CSS untuk persentase
			} else {
				preview.src = 'assets/img/1.png';
				preview.style.width = '50%'; // (Opsional) tetap gunakan width default
			}
		}
	});
}

