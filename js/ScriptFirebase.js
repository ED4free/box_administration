// Initialize Firebase
var config = {
    apiKey: "AIzaSyAojKT5XJW2uxNPuLOVEK_BGQGj6hBdEog",
    authDomain: "edbox-f48e7.firebaseapp.com",
    databaseURL: "https://edbox-f48e7.firebaseio.com",
    projectId: "edbox-f48e7",
    storageBucket: "edbox-f48e7.appspot.com",
    messagingSenderId: "1089393359801"
};
firebase.initializeApp(config);
console.log("firebase initialize!");
var db = firebase.database();

function add_to_db(path, item) {
    var ref = db.ref(path);
    
    ref.set(item);
}

function remove_to_db(path) {
    var ref = db.ref(path);
    
    ref.remove();
}

function get_data_from_db(path) {
    var ref = db.ref(path);  
}
