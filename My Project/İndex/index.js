function toggleNav() {
    const sidebar = document.getElementById("sidebarNav");
    if (sidebar.style.width === "250px") {
        closeNav();
    } else {
        openNav();
    }
}

function openNav() {
    document.getElementById("sidebarNav").style.width = "250px";
}

function closeNav() {
    document.getElementById("sidebarNav").style.width = "0";
}


const labModal = document.getElementById("labSonuclariModal");
const labBtn = document.getElementById("labSonuclariBtn");
const labClose = document.querySelector(".close");

labBtn.onclick = function () {
    labModal.style.display = "block";
    labModal.style.zIndex = "1111";
};

labClose.onclick = function () {
    labModal.style.display = "none";
};

window.onclick = function (event) {
    if (event.target === labModal) {
        labModal.style.display = "none";
    }
};

// Sayfa yüklendiğinde verileri çekme fonksiyonu
function fetchVeterinerler() {
	fetch('get_veterinerler.php')
		.then(response => response.json())
		.then(data => {
			const veterinerList = document.getElementById('veterinerList');
			veterinerList.innerHTML = ''; 

			data.forEach(veteriner => {
				const vetItem = document.createElement('div');
				vetItem.classList.add('veteriner-item');

				const adSoyad = document.createElement('h4');
				adSoyad.textContent = veteriner.adSoyad;
				vetItem.appendChild(adSoyad);

				const klinik = document.createElement('p');
				klinik.textContent = `Klinik: ${veteriner.klinik}`;
				vetItem.appendChild(klinik);

				const lokasyon = document.createElement('p');
				lokasyon.textContent = `Lokasyon: ${veteriner.lokasyon}`;
				vetItem.appendChild(lokasyon);

				veterinerList.appendChild(vetItem);
			});
			
		})
		.catch(error => console.log(error));
}


function searchVeteriner() {
	const input = document.getElementById('searchVeteriner').value.toLowerCase();
	const items = document.querySelectorAll('.veteriner-item');

	items.forEach(item => {
		const name = item.textContent.toLowerCase();
		if (name.includes(input)) {
			item.style.display = 'block';
		} else {
			item.style.display = 'none';
		}
	});
}


const vetModal = document.getElementById('nobetciVeterinerModal');
const vetBtn = document.getElementById('nobetciVeterinerBtn');
const vetClose = document.querySelector('#nobetciVeterinerModal .close');

vetBtn.onclick = function () {
	vetModal.style.display = 'block';
	fetchVeterinerler(); 
};

vetClose.onclick = function () {
	vetModal.style.display = 'none';
};

window.onclick = function (event) {
	if (event.target === vetModal) {
		vetModal.style.display = 'none';
	}
};