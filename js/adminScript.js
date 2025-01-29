jQuery(document).ready(function () {
  jQuery("#upload_image_button").on("click", function (event) {
    event.preventDefault(); // prevent default link click and page refresh
    const customUploader = wp
      .media({
        title: "Select Video", // modal window title

        button: {
          text: "Use this Video", // button label text
        },
        multiple: false,
      })
      .on("select", function () {
        // it also has "open" and "close" events
        const attachment = customUploader
          .state()
          .get("selection")
          .first()
          .toJSON();
        // add image instead of "Upload Image"
        jQuery("#testingVideo").val(attachment.url);
        button.next().show(); // show "Remove image" link
        button.next().next().val(attachment.id); // Populate the hidden field with image ID
      });

    // already selected images
    customUploader.on("open", function () {
      if (imageId) {
        const selection = customUploader.state().get("selection");
        attachment = wp.media.attachment(imageId);
        attachment.fetch();
        selection.add(attachment ? [attachment] : []);
      }
    });

    customUploader.open();
  });
});
