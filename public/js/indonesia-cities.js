// Daftar Kabupaten/Kota di Indonesia
const indonesiaCities = [
    // Aceh
    "Banda Aceh", "Sabang", "Lhokseumawe", "Langsa", "Subulussalam",
    "Aceh Besar", "Aceh Barat", "Aceh Barat Daya", "Aceh Jaya", "Aceh Selatan",
    "Aceh Singkil", "Aceh Tamiang", "Aceh Tengah", "Aceh Tenggara", "Aceh Timur",
    "Aceh Utara", "Bener Meriah", "Bireuen", "Gayo Lues", "Nagan Raya",
    "Pidie", "Pidie Jaya", "Simeulue",
    
    // Sumatera Utara
    "Medan", "Binjai", "Padang Sidempuan", "Pematang Siantar", "Sibolga",
    "Tanjung Balai", "Tebing Tinggi", "Gunungsitoli",
    "Asahan", "Batubara", "Dairi", "Deli Serdang", "Humbang Hasundutan",
    "Karo", "Labuhanbatu", "Labuhanbatu Selatan", "Labuhanbatu Utara",
    "Langkat", "Mandailing Natal", "Nias", "Nias Barat", "Nias Selatan",
    "Nias Utara", "Padang Lawas", "Padang Lawas Utara", "Pakpak Bharat",
    "Samosir", "Serdang Bedagai", "Simalungun", "Tapanuli Selatan",
    "Tapanuli Tengah", "Tapanuli Utara", "Toba",
    
    // Sumatera Barat
    "Padang", "Bukittinggi", "Padang Panjang", "Pariaman", "Payakumbuh",
    "Sawahlunto", "Solok",
    "Agam", "Dharmasraya", "Kepulauan Mentawai", "Lima Puluh Kota",
    "Padang Pariaman", "Pasaman", "Pasaman Barat", "Pesisir Selatan",
    "Sijunjung", "Solok Selatan", "Tanah Datar",
    
    // Riau
    "Pekanbaru", "Dumai",
    "Bengkalis", "Indragiri Hilir", "Indragiri Hulu", "Kampar",
    "Kepulauan Meranti", "Kuantan Singingi", "Pelalawan", "Rokan Hilir",
    "Rokan Hulu", "Siak",
    
    // Kepulauan Riau
    "Batam", "Tanjung Pinang",
    "Bintan", "Karimun", "Kepulauan Anambas", "Lingga", "Natuna",
    
    // Jambi
    "Jambi", "Sungai Penuh",
    "Batang Hari", "Bungo", "Kerinci", "Merangin", "Muaro Jambi",
    "Sarolangun", "Tanjung Jabung Barat", "Tanjung Jabung Timur", "Tebo",
    
    // Sumatera Selatan
    "Palembang", "Lubuklinggau", "Pagar Alam", "Prabumulih",
    "Banyuasin", "Empat Lawang", "Lahat", "Muara Enim", "Musi Banyuasin",
    "Musi Rawas", "Musi Rawas Utara", "Ogan Ilir", "Ogan Komering Ilir",
    "Ogan Komering Ulu", "Ogan Komering Ulu Selatan", "Ogan Komering Ulu Timur",
    "Penukal Abab Lematang Ilir",
    
    // Kepulauan Bangka Belitung
    "Pangkal Pinang",
    "Bangka", "Bangka Barat", "Bangka Selatan", "Bangka Tengah",
    "Belitung", "Belitung Timur",
    
    // Bengkulu
    "Bengkulu",
    "Bengkulu Selatan", "Bengkulu Tengah", "Bengkulu Utara", "Kaur",
    "Kepahiang", "Lebong", "Mukomuko", "Rejang Lebong", "Seluma",
    
    // Lampung
    "Bandar Lampung", "Metro",
    "Lampung Barat", "Lampung Selatan", "Lampung Tengah", "Lampung Timur",
    "Lampung Utara", "Mesuji", "Pesawaran", "Pesisir Barat", "Pringsewu",
    "Tanggamus", "Tulang Bawang", "Tulang Bawang Barat", "Way Kanan",
    
    // DKI Jakarta
    "Jakarta Barat", "Jakarta Pusat", "Jakarta Selatan", "Jakarta Timur",
    "Jakarta Utara", "Kepulauan Seribu",
    
    // Banten
    "Cilegon", "Serang", "Tangerang", "Tangerang Selatan",
    "Lebak", "Pandeglang", "Tangerang",
    
    // Jawa Barat
    "Bandung", "Banjar", "Bekasi", "Bogor", "Cimahi", "Cirebon",
    "Depok", "Sukabumi", "Tasikmalaya",
    "Bandung", "Bandung Barat", "Bekasi", "Bogor", "Ciamis", "Cianjur",
    "Cirebon", "Garut", "Indramayu", "Karawang", "Kuningan", "Majalengka",
    "Pangandaran", "Purwakarta", "Subang", "Sukabumi", "Sumedang",
    "Tasikmalaya",
    
    // Jawa Tengah
    "Magelang", "Pekalongan", "Salatiga", "Semarang", "Surakarta", "Tegal",
    "Banjarnegara", "Banyumas", "Batang", "Blora", "Boyolali", "Brebes",
    "Cilacap", "Demak", "Grobogan", "Jepara", "Karanganyar", "Kebumen",
    "Kendal", "Klaten", "Kudus", "Magelang", "Pati", "Pekalongan",
    "Pemalang", "Purbalingga", "Purworejo", "Rembang", "Semarang",
    "Sragen", "Sukoharjo", "Tegal", "Temanggung", "Wonogiri", "Wonosobo",
    
    // DI Yogyakarta
    "Yogyakarta",
    "Bantul", "Gunungkidul", "Kulon Progo", "Sleman",
    
    // Jawa Timur
    "Batu", "Blitar", "Kediri", "Madiun", "Malang", "Mojokerto",
    "Pasuruan", "Probolinggo", "Surabaya",
    "Bangkalan", "Banyuwangi", "Blitar", "Bojonegoro", "Bondowoso",
    "Gresik", "Jember", "Jombang", "Kediri", "Lamongan", "Lumajang",
    "Madiun", "Magetan", "Malang", "Mojokerto", "Nganjuk", "Ngawi",
    "Pacitan", "Pamekasan", "Pasuruan", "Ponorogo", "Probolinggo",
    "Sampang", "Sidoarjo", "Situbondo", "Sumenep", "Trenggalek",
    "Tuban", "Tulungagung",
    
    // Bali
    "Denpasar",
    "Badung", "Bangli", "Buleleng", "Gianyar", "Jembrana", "Karangasem",
    "Klungkung", "Tabanan",
    
    // Nusa Tenggara Barat
    "Bima", "Mataram",
    "Bima", "Dompu", "Lombok Barat", "Lombok Tengah", "Lombok Timur",
    "Lombok Utara", "Sumbawa", "Sumbawa Barat",
    
    // Nusa Tenggara Timur
    "Kupang",
    "Alor", "Belu", "Ende", "Flores Timur", "Kupang", "Lembata",
    "Malaka", "Manggarai", "Manggarai Barat", "Manggarai Timur",
    "Nagekeo", "Ngada", "Rote Ndao", "Sabu Raijua", "Sikka",
    "Sumba Barat", "Sumba Barat Daya", "Sumba Tengah", "Sumba Timur",
    "Timor Tengah Selatan", "Timor Tengah Utara",
    
    // Kalimantan Barat
    "Pontianak", "Singkawang",
    "Bengkayang", "Kapuas Hulu", "Kayong Utara", "Ketapang", "Kubu Raya",
    "Landak", "Melawi", "Mempawah", "Sambas", "Sanggau", "Sekadau", "Sintang",
    
    // Kalimantan Tengah
    "Palangka Raya",
    "Barito Selatan", "Barito Timur", "Barito Utara", "Gunung Mas",
    "Kapuas", "Katingan", "Kotawaringin Barat", "Kotawaringin Timur",
    "Lamandau", "Murung Raya", "Pulang Pisau", "Seruyan", "Sukamara",
    
    // Kalimantan Selatan
    "Banjarbaru", "Banjarmasin",
    "Balangan", "Banjar", "Barito Kuala", "Hulu Sungai Selatan",
    "Hulu Sungai Tengah", "Hulu Sungai Utara", "Kotabaru", "Tabalong",
    "Tanah Bumbu", "Tanah Laut", "Tapin",
    
    // Kalimantan Timur
    "Balikpapan", "Bontang", "Samarinda",
    "Berau", "Kutai Barat", "Kutai Kartanegara", "Kutai Timur",
    "Mahakam Ulu", "Paser", "Penajam Paser Utara",
    
    // Kalimantan Utara
    "Tarakan",
    "Bulungan", "Malinau", "Nunukan", "Tana Tidung",
    
    // Sulawesi Utara
    "Bitung", "Kotamobagu", "Manado", "Tomohon",
    "Bolaang Mongondow", "Bolaang Mongondow Selatan", "Bolaang Mongondow Timur",
    "Bolaang Mongondow Utara", "Kepulauan Sangihe", "Kepulauan Siau Tagulandang Biaro",
    "Kepulauan Talaud", "Minahasa", "Minahasa Selatan", "Minahasa Tenggara",
    "Minahasa Utara",
    
    // Sulawesi Tengah
    "Palu",
    "Banggai", "Banggai Kepulauan", "Banggai Laut", "Buol", "Donggala",
    "Morowali", "Morowali Utara", "Parigi Moutong", "Poso", "Sigi",
    "Tojo Una-Una", "Toli-Toli",
    
    // Sulawesi Selatan
    "Makassar", "Palopo", "Parepare",
    "Bantaeng", "Barru", "Bone", "Bulukumba", "Enrekang", "Gowa",
    "Jeneponto", "Kepulauan Selayar", "Luwu", "Luwu Timur", "Luwu Utara",
    "Maros", "Pangkajene dan Kepulauan", "Pinrang", "Sidenreng Rappang",
    "Sinjai", "Soppeng", "Takalar", "Tana Toraja", "Toraja Utara", "Wajo",
    
    // Sulawesi Tenggara
    "Baubau", "Kendari",
    "Bombana", "Buton", "Buton Selatan", "Buton Tengah", "Buton Utara",
    "Kolaka", "Kolaka Timur", "Kolaka Utara", "Konawe", "Konawe Kepulauan",
    "Konawe Selatan", "Konawe Utara", "Muna", "Muna Barat", "Wakatobi",
    
    // Gorontalo
    "Gorontalo",
    "Boalemo", "Bone Bolango", "Gorontalo", "Gorontalo Utara", "Pohuwato",
    
    // Sulawesi Barat
    "Majene", "Mamasa", "Mamuju", "Mamuju Tengah", "Pasangkayu", "Polewali Mandar",
    
    // Maluku
    "Ambon", "Tual",
    "Buru", "Buru Selatan", "Kepulauan Aru", "Maluku Barat Daya",
    "Maluku Tengah", "Maluku Tenggara", "Maluku Tenggara Barat", "Seram Bagian Barat",
    "Seram Bagian Timur",
    
    // Maluku Utara
    "Ternate", "Tidore Kepulauan",
    "Halmahera Barat", "Halmahera Selatan", "Halmahera Tengah", "Halmahera Timur",
    "Halmahera Utara", "Kepulauan Sula", "Pulau Morotai", "Pulau Taliabu",
    
    // Papua Barat
    "Sorong",
    "Fakfak", "Kaimana", "Manokwari", "Manokwari Selatan", "Maybrat",
    "Pegunungan Arfak", "Raja Ampat", "Sorong", "Sorong Selatan",
    "Tambrauw", "Teluk Bintuni", "Teluk Wondama",
    
    // Papua
    "Jayapura",
    "Asmat", "Biak Numfor", "Boven Digoel", "Deiyai", "Dogiyai", "Intan Jaya",
    "Jayapura", "Jayawijaya", "Keerom", "Kepulauan Yapen", "Lanny Jaya",
    "Mamberamo Raya", "Mamberamo Tengah", "Mappi", "Merauke", "Mimika",
    "Nabire", "Nduga", "Paniai", "Pegunungan Bintang", "Puncak", "Puncak Jaya",
    "Sarmi", "Supiori", "Tolikara", "Waropen", "Yahukimo", "Yalimo"
];
