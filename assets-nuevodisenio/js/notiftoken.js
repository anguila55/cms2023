var urlRegisterNotif = 'https://rondasbtbox.onlife.com.ar/api/app.php/register';


var config = {
	apiKey: "AIzaSyBzuLNfsqw5kZxYOmRVDRgoqBfKQQi3lrA",
    authDomain: "btbox1demo.firebaseapp.com",
    projectId: "btbox1demo",
    storageBucket: "btbox1demo.appspot.com",
    messagingSenderId: "826280349236",
    appId: "1:826280349236:web:a60b3cbe61dc0ebc0d4461",
    measurementId: "G-2E9B41LHV2"
};

firebase.initializeApp(config);

const messaging = firebase.messaging();

messaging
	.requestPermission()
	.then(() => {
		// console.log("Notifications allowed");
		return messaging.getToken();
	})
	.then(token => {
		// console.log("Token Is : " + token);
		var percodigo = $('#percodnotif').val();
		var uid = token.substring(1, 50);
		var data = {"uid":uid,
					"provider":"FCMW",
					"id":token,
					"PERCODIGO":percodigo};
		$.ajax({
			type:"POST",
			url: urlRegisterNotif,
			data:data,
		}).done(function(rsp){
		});
		
	})
	.catch(err => {
		// console.log('Error: '+err);
		// console.log("No permission to send push", err);
	});
  
messaging.onMessage(payload => {
	// console.log("Message received. ", payload);
	const { title, ...options } = payload.notification;
	
	self.registration.showNotification(
		title,
		options
	);
});



  
