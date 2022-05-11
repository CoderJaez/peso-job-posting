$(document).ready(function () {
	$(document).on("click", ".job-browse-card", function () {
		url = $(this).find("h5 a").attr("href");
		id = $(this).find("h5 a").data("id");
		sessionStorage.setItem("jsID", id);
		window.location = url;
	});

	$(".btnJobSeeker").click(function () {
		window.location = base_url + "register";
	});

	$(".btnJobSeekerLogout").click(function () {
		window.location = base_url + "logout";
	});

	// var wrap = $('.wrap');
	// $(document).on("scroll", wrap, function(e) {
	//     if (window.pageYOffset > 90 && $(window).width() > 960) {
	//         $('.registration-form').addClass('stay-on-top');
	//     } else {
	//         $('.registration-form').removeClass('stay-on-top');
	//     }
	// });

	$(".btnApplyNow").click(function () {
		var id = sessionStorage.getItem("jsID");
		$.ajax({
			url: base_url + "/Jobs/applyJob",
			method: "post",
			data: { id: id },
			beforeSend: function () {
				$("#loading_screen").addClass("loading").removeClass("remove-loading");
			},
			success: function (result) {
				if (result.success) {
					$.bootstrapGrowl(result.msg, { type: "success" });
				}
				$(".btnApplyNow").addClass("disabled").prop("disabled", true);
			},
			complete: function () {
				$("#loading_screen").addClass("remove-loading").removeClass("loading");
			},
		});
	});

	$("select[name=job_category]").change(function () {
		var url = $(this).val();
		if (url != "") {
			window.location = url;
		}
	});

	$("select[name=job_location]").change(function () {
		var url = $(this).val();
		if (url != "") {
			window.location = url;
		}
	});
	//quick apply
	$("#quickApplyForm").submit(function (e) {
		e.preventDefault();
		$("input[name=id]").val(sessionStorage.getItem("jsID"));
		var data = new FormData(this);
		$.ajax({
			url: base_url + "/Jobs/quick_apply",
			method: "post",
			dataType: "json",
			processData: false,
			contentType: false,
			data: data,
			beforeSend: function () {
				$("#loading_screen").addClass("loading").removeClass("remove-loading");
			},
			success: function (result) {
				if (result.success) {
					$.bootstrapGrowl(result.msg, {
						type: "success",
					});
					setTimeout(redirectToMainPage, 1000);
				} else {
					$.bootstrapGrowl(result.msg, {
						type: "danger",
					});
				}
			},
			complete: function () {
				$("#loading_screen").addClass("remove-loading").addClass("loading");
			},
		});
	});

	function redirectToMainPage() {
		window.location = base_url;
	}
	//END OF DOCUMENT READY
});
