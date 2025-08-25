// Function for displaying uploaded files
const onUploadSuccess = (elForUploadedFiles) =>
   (file, response) => {
      const url = response.uploadURL
      const fileName = file.name

      const li = document.createElement('li')
      const a = document.createElement('a')
      a.href = url
      a.target = '_blank'
      a.appendChild(document.createTextNode(fileName))
      li.appendChild(a)

      document.querySelector(elForUploadedFiles).appendChild(li)
   }


var uppy = Uppy.Core({autoProceed: true})
   .use(Uppy.Dashboard, {
      inline: true,
      target: '.dropflie-wrap .for-dashboard',
      showRemoveButtonAfterComplete: true,
      strings: {
         dashboardTitle: 'أضف ملف',
      }
   })
   .use(Uppy.Tus, {
      endpoint: 'https://tusd.tusdemo.net/files/'
   })
   .use(Uppy.ProgressBar, {
      target: '.example-one .for-ProgressBar',
      hideAfterFinish: true
   })
   uppy.use(Uppy.ThumbnailGenerator, {
      id: 'ThumbnailGenerator',
      thumbnailWidth: 200,
      thumbnailHeight: 200,
      // thumbnailType: 'image/jpeg',
      waitForThumbnailsBeforeUpload: true,
    })

   .on('upload-success', onUploadSuccess('.example-one .uploaded-files ol'))
   .on('thumbnail:generated', (file, preview) => {
      const img = document.createElement('img')
      img.src = preview
      img.width = 100
      document.getElementById('prev-img').appendChild(img)
    })

    .on('upload-success', (file, response) => {
      if (response.status === 200) {
         uppy.removeFile(file.id);
      }
   })  
   .on('file-removed', (file, reason) => {
      if (reason === 'removed-by-user') {
        sendDeleteRequestForFile(file)
      }
    })

uppy.on('complete', (result) => {
   console.log('Upload complete! We’ve uploaded these files:', result.successful)
})
