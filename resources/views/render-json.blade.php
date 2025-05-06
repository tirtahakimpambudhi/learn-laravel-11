<x-base>
    <x-slot:title>
        Render Data to JSON Format
    </x-slot:title>

    <h1>With Built-in PHP Function</h1>

    <script>
        // Mengambil data dari PHP dan encode ke JSON
        const datas = <?php echo json_encode($data); ?>;

        // Buat elemen <ul>
        const list = document.createElement('ul');

        // Iterasi data dan buat <li> untuk setiap item
        datas?.forEach((data) => {
            const item = document.createElement('li');
            item.innerHTML = `
                <h5>ID: ${data._id}</h5>
                <h6>Name: ${data.name}</h6>
            `;
            list.appendChild(item);
        });

        // Tambahkan <ul> ke dalam body
        document.body.appendChild(list);
    </script>
    <br>
    <h1>With Library By Provide Laravel</h1>
    <script>
        // Mengambil data dari PHP dan encode ke JSON
        const dataFormatLaravel = {{ Illuminate\Support\Js::from($data) }};

        // Buat elemen <ul>
        const listFromLaravel = document.createElement('ul');

        // Iterasi data dan buat <li> untuk setiap item
        dataFormatLaravel?.forEach((data) => {
            const item = document.createElement('li');
            item.innerHTML = `
                <h5>ID: ${data._id}</h5>
                <h6>Name: ${data.name}</h6>
            `;
            listFromLaravel.appendChild(item);
        });

        // Tambahkan <ul> ke dalam body
        document.body.appendChild(listFromLaravel);
    </script>
</x-base>
