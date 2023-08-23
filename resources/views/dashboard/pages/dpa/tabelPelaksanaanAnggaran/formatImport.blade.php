<table style="border: 1px solid black;background-color : #C8C8C8">
    <thead align="center">
        <tr style="border: 1px solid black">
            <th align="center"
                style="vertical-align: center;border: 1px solid black;font-weight : bold;background-color : #C8C8C8"
                width="50">
                Kegiatan
            </th>
            <th align="center"
                style="vertical-align: center;border: 1px solid black;font-weight : bold;background-color : #C8C8C8"
                width="20">
                No.Rek. Keg.SKPD
            </th>
            <th align="center"
                style="vertical-align: center;border: 1px solid black;font-weight : bold;background-color : #C8C8C8"
                width="50">
                Program
            </th>
            <th align="center"
                style="vertical-align: center;border: 1px solid black;font-weight : bold;background-color : #C8C8C8"
                width="20">
                No.Rek
            </th>
            <th align="center"
                style="vertical-align: center;border: 1px solid black;font-weight : bold;background-color : #f67777"
                width="50">
                Biro Organisasi
            </th>
            <th align="center"
                style="vertical-align: center;border: 1px solid black;font-weight : bold;background-color : #C8C8C8"
                width="30">
                Jumlah Anggaran
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sekretariatDaerah as $item)
            @for ($i = 0; $i < 5; $i++)
                <tr style="border: 1px solid black">
                    <td align="center" style="vertical-align: center;border: 1px solid black">

                    </td>
                    <td align="center" style="vertical-align: center;border: 1px solid black">

                    </td>
                    <td align="center" style="vertical-align: center;border: 1px solid black">

                    </td>
                    <td align="center" style="vertical-align: center;border: 1px solid black">

                    </td>
                    <td align="center"
                        style="vertical-align: center;border: 1px solid black;background-color : #f67777">
                        {{ $item->nama }}
                    </td>
                    <td align="center" style="vertical-align: center;border: 1px solid black">

                    </td>
                </tr>
            @endfor
        @endforeach
    </tbody>
</table>
