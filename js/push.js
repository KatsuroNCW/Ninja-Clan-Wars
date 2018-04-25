var showButton = document.querySelector(".show-btn");

window.onload = function () {
    Push.Permission.request();
}

showButton.onclick = function () {
    Push.create("Ninja Clan Wars", {
		body: "Przykładowe powiadomienie!",
        icon: "style/img/logo.png",
        timeout: 5000,
        onClick: function() {
            console.log(this);
        }
    });
};