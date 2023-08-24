 <tr class="align-items-center program-kegiatan" data-key="{{ $dataKey }}">
     <td class="align-middle">
         @component('dashboard.components.formElements.select', [
             'id' => 'program-' . $dataKey,
             'name' => 'program-' . $dataKey,
             'class' => 'select2 program col-12',
             'dataKey' => $dataKey,
         ])
             @slot('options')
                 @foreach ($daftarProgram as $program)
                     <option value="{{ $program->id }}">
                         {{ $program->nama . ' (' . $program->no_rek . ')' }}</option>
                 @endforeach
             @endslot
         @endcomponent
     </td>
     <td class="align-middle">
         @component('dashboard.components.formElements.select', [
             'id' => 'kegiatan-' . $dataKey,
             'name' => 'kegiatan-' . $dataKey,
             'class' => 'select2 kegiatan',
             'dataKey' => $dataKey,
         ])
         @endcomponent
     </td>
     <td class="align-middle">
         <div style="width:150px">
             @component('dashboard.components.formElements.input', [
                 'type' => 'text',
                 'id' => 'jumlah-anggaran-' . $dataKey,
                 'name' => 'jumlah-anggaran-' . $dataKey,
                 'class' => 'uang jumlah-anggaran',
                 'placeholder' => 'Masukkan Jumlah Anggaran',
                 'dataKey' => $dataKey,
                 'value' => '',
             ])
             @endcomponent
         </div>
     </td>
     <td class="text-center">
         <button type="button" class="btn btn-danger btn-sm btn-delete-program-kegiatan"
             data-key="{{ $dataKey }}"><i class="far fa-trash-alt"></i>
             Hapus</button>
     </td>
 </tr>
