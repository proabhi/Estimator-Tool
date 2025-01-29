jQuery(document).ready(function () {
  jQuery(".basmentDetail").click(function () {
    jQuery(".BasementDetailSuccess").show();
  });

  jQuery('.details_preference input[name="preference"]').on(
    "change",
    function () {
      var name = jQuery('input[name="name"]').val();
      var email = jQuery('input[name="email"]').val();
      var phone = jQuery('input[name="phone"]').val();
      var formType = this.value;
      if (this.value == "standard") {
        jQuery(".enhanced-field").hide();
        jQuery("#myfrm").show();
      }

      if (this.value == "enhanced") {
        jQuery(".enhanced-field").show();
        jQuery("#myfrm").show();
      }
    }
  );
  jQuery("#Submitfrm").on("click", function () {
    var TotalSqft = jQuery("#sqft").val();
    var formType = jQuery('input[name="preference"]:checked').val();
    var ceilingHeight = jQuery('input[name="ceilings"]:checked').val();
    var bathroom = jQuery("#bathroomPref").val();
    var bathroomDrains = jQuery("#bathroomDrains").val();
    var kitchenette = jQuery("#kitchenettePref").val();
    var kitchenetteDrains = jQuery("#kitchenetteDrains").val();
    var ceilingType = jQuery(
      'input[name="enhanced-ceilingType"]:checked'
    ).val();
    var name = jQuery('input[name="name"]').val();
    var email = jQuery('input[name="email"]').val();
    var phone = jQuery('input[name="phone"]').val();

    /************************************ Validation Start ***********************************************************/

    var erros = [];

    if (name == "") {
      jQuery('input[name="name"]').focus();
      jQuery('input[name="name"]').addClass("errorHighlight");
      erros.push(true);

      jQuery("#nameError").html("<p>Name is required</p>");
    } else {
      jQuery('input[name="name"]').blur();
      jQuery('input[name="name"]').removeClass("errorHighlight");
      erros.push(false);
      jQuery("#nameError").hide();
    }
    if (email == "") {
      jQuery('input[name="email"]').focus();
      jQuery('input[name="email"]').addClass("errorHighlight");
      erros.push(true);
      jQuery("#emailError").html("<p>Email is required</p>");
    } else {
      jQuery('input[name="email"]').blur();
      jQuery('input[name="email"]').removeClass("errorHighlight");
      erros.push(false);
      jQuery("#emailError").hide();
    }
    if (email != "") {
      var check = validateEmails(email);
      if (!check) {
        jQuery('input[name="email"]').focus();
        jQuery('input[name="email"]').css("border", "1px solid #ff0000");
        erros.push(true);
        jQuery("#emailError").html("<p>Email is not correct</p>");
      }
      erros.push(false);
      jQuery("#emailError").hide();
    }
    if (phone == "") {
      jQuery('input[name="phone"]').focus();
      jQuery('input[name="phone"]').addClass("errorHighlight");
      erros.push(true);
      jQuery("#phoneError").html("<p>Phone number is required</p>");
    } else {
      jQuery('input[name="phone"]').blur();
      jQuery('input[name="phone"]').removeClass("errorHighlight");
      erros.push(false);
      jQuery("#phoneError").hide();
    }
    if (formType == "") {
      jQuery('input[name="preference"]').focus();
      jQuery('input[name="preference"]').addClass("errorHighlight");
      erros.push(true);
    } else {
      jQuery('input[name="preference"]').blur();
      jQuery('input[name="preference"]').removeClass("errorHighlight");
      erros.push(false);
    }
    if (TotalSqft == "") {
      jQuery("#sqft").focus();
      jQuery("#sqft").addClass("errorHighlight");
      erros.push(true);
      jQuery("#sqftError").html("<p>This field is required</p>");
    } else {
      jQuery("#sqft").blur();
      jQuery("#sqft").removeClass("errorHighlight");
      erros.push(false);
      jQuery("#sqftError").hide();
    }
    if (ceilingHeight == undefined) {
      jQuery('input[name="ceilings"]').focus();
      jQuery('input[name="ceilings"]').addClass("errorHighlight");
      erros.push(true);

      jQuery("#ceilingsError").html("<p>This field is required</p>");
    } else {
      jQuery('input[name="ceilings"]').blur();
      jQuery('input[name="ceilings"]').removeClass("errorHighlight");
      erros.push(false);
      jQuery("#ceilingsError").hide();
    }
    if (bathroom == null) {
      jQuery("#bathroomPref").focus();
      jQuery("#bathroomPref").addClass("errorHighlight");
      erros.push(true);
      jQuery("#bathprefError").html("<p>Bathroom Preference is required</p>");
    } else {
      jQuery("#bathroomPref").blur();
      jQuery("#bathroomPref").removeClass("errorHighlight");
      erros.push(false);
      jQuery("#bathprefError").hide();
    }
    if (bathroomDrains == null) {
      jQuery("#bathroomDrains").focus();
      jQuery("#bathroomDrains").addClass("errorHighlight");
      erros.push(true);
      jQuery("#bathdrainError").html("<p>This field is required</p>");
    } else {
      jQuery("#bathroomDrains").blur();
      jQuery("#bathroomDrains").removeClass("errorHighlight");
      erros.push(false);
      jQuery("#bathdrainError").hide();
    }
    if (kitchenette == null) {
      jQuery("#kitchenettePref").focus();
      jQuery("#kitchenettePref").addClass("errorHighlight");
      erros.push(true);
      jQuery("#kitchenprefError").html(
        "<p>Kitchenette Preference is required</p>"
      );
    } else {
      jQuery("#kitchenettePref").blur();
      jQuery("#kitchenettePref").removeClass("errorHighlight");
      erros.push(false);
      jQuery("#kitchenprefError").hide();
    }
    if (kitchenetteDrains == null) {
      jQuery("#kitchenetteDrains").focus();
      jQuery("#kitchenetteDrains").addClass("errorHighlight");
      erros.push(true);
      jQuery("#kitchendrainError").html("<p>This field is required</p>");
    } else {
      jQuery("#kitchenetteDrains").blur();
      jQuery("#kitchenetteDrains").removeClass("errorHighlight");
      erros.push(false);
      jQuery("#kitchendrainError").hide();
    }
    if (formType == "enhanced") {
      if (ceilingType == undefined) {
        jQuery('input[name="enhanced-ceilingType"]').focus();
        jQuery('input[name="enhanced-ceilingType"]').addClass("errorHighlight");
        erros.push(true);

        jQuery("#ceilingtypeError").html("<p>This field is required</p>");
      } else {
        jQuery('input[name="enhanced-ceilingType"]').blur();
        jQuery('input[name="enhanced-ceilingType"]').removeClass(
          "errorHighlight"
        );
        erros.push(false);
        jQuery("#ceilingtypeError").hide();
      }
    }

    /************************************ Validation End ***********************************************************/

    var squareftRate = CalcSqft(TotalSqft, formType);
    var ceilingHeightRate = ceilingHeightRateCalc(ceilingHeight, TotalSqft);
    var bathroomTypeRate = BathroomTypeRateCalc(bathroom);
    var bathroomDrainsRate = bathroomDrainCalc(bathroomDrains);
    var kitchenetteRate = kitchenetteRateCalc(kitchenette);
    var kitchenetteDrainsRate = kitchenetteDrainCalc(kitchenetteDrains);
    var ceilingTypeRate = ceilingTypeRateCalc(ceilingType);

    var totalRate =
      squareftRate +
      ceilingHeightRate +
      bathroomTypeRate +
      bathroomDrainsRate +
      kitchenetteRate +
      kitchenetteDrainsRate -
      ceilingTypeRate;

    var mydata = {
      formType: formType,
      name: name,
      email: email,
      phone: phone,
      squareftRate: squareftRate,
      ceilingHeightRate: ceilingHeightRate,
      bathroomTypeRate: bathroomTypeRate,
      bathroomDrainsRate: bathroomDrainsRate,
      kitchenetteRate: kitchenetteRate,
      kitchenetteDrainsRate: kitchenetteDrainsRate,
      ceilingTypeRate: ceilingTypeRate,
      totalRate: totalRate,
    };

    if (jQuery.inArray(true, erros) !== -1) {
      console.log("error while submitting form");
    } else {
      jQuery("#myModal").css("display", "flex");
      var video = jQuery("#myVideo")[0];
      jQuery("#myVideo").attr('autoplay');
      jQuery("#myVideo").prop("muted", false);
      video.play();
      //video.muted = false;
      // console.log(video);

      jQuery("#loader-container").show();
      var dynamic_duration = Math.floor(video.duration);

      // Delay the AJAX call for 30 seconds
      setTimeout(function () {
        // Your AJAX call here
        jQuery.ajax({
          url: "/wp-content/plugins/basement-estimator/pdfgen.php",
          method: "POST",
          data: mydata,
          success: function (data) {
            jQuery("#preference")[0].reset();

              setInterval(function () {
                var total_duration = Math.floor(video.duration);
                current_duration = Math.floor(video.currentTime);
                console.log(total_duration);
                console.log(current_duration);
                if (total_duration == current_duration) {
                  jQuery("#myModal").fadeOut();
                  jQuery("#loader-container").hide();
                  jQuery("#SuccessResponseModal").css("display", "block");
                }
              }, 1000);

          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX call failed: ", textStatus, errorThrown);
          },
        });
       
        //video.pause();
      }, 2000); // 30000ms = 30 seconds
    }
      jQuery("#close-btn").click(function () {
      jQuery("#loader-container").hide();
      jQuery("#myModal").hide();
      jQuery("#SuccessResponseModal").css("display", "block");
   
      video.pause();
    });   

      
  });
	
	jQuery("button.close.SuccessModalClose").click(function () {
		jQuery("#SuccessResponseModal").addClass("intro");

      jQuery("#SuccessResponseModal").css("display", "none");
		
      location.reload();
		
	
    });
      
    
      
	
  //dynamic_duration * 1000
  function CalcSqft(val, formtype) {
    if (formtype == "standard") {
      if (val <= 500) {
        var rate = parseInt(val) * 35;
      } else {
        rate = parseInt(val) * 30;
      }
      return rate;
    } else {
      if (val <= 500) {
        rate = parseInt(val) * 38;
        return rate;
      } else {
        rate = parseInt(val) * 35;
        return rate;
      }
    }
  }

  function ceilingHeightRateCalc(height, squareft) {
    if (height == "yes") {
      var heightrate = squareft * 2;
    } else {
      heightrate = 0;
    }
    return heightrate;
  }

  function BathroomTypeRateCalc(type) {
    if (type == "fullBath") {
      var bathrate = 6000;
    } else if (type == "halfBath") {
      bathrate = 3000;
    } else {
      bathrate = 0;
    }
    return bathrate;
  }

  function bathroomDrainCalc(drainCheck) {
    if (drainCheck == "no" || drainCheck == "idk") {
      var drainrate = 1400;
    } else {
      drainrate = 0;
    }
    return drainrate;
  }

  function kitchenetteRateCalc(kitchenetteType) {
    if (kitchenetteType == "dryBar") {
      var kitchenrate = 3500;
    } else if (kitchenetteType == "withSink") {
      kitchenrate = 6000;
    } else {
      kitchenrate = 0;
    }
    return kitchenrate;
  }

  function kitchenetteDrainCalc(kitchenDrainCheck) {
    if (kitchenDrainCheck == "no" || kitchenDrainCheck == "idk") {
      var kitchendrainrate = 1400;
    } else {
      kitchendrainrate = 0;
    }
    return kitchendrainrate;
  }

  function ceilingTypeRateCalc(ceilingTypeCheck) {
    if (ceilingTypeCheck == "sprayblack") {
      var ceilingtypecheckrate = 500;
    } else {
      ceilingtypecheckrate = 0;
    }
    return ceilingtypecheckrate;
  }
});

function validateEmails(email) {
  var re =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}