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
         <span class="pt-5" id="jumlah-anggaran-{{ $dataKey }}">
             0
         </span>
     </td>
     <td class="align-middle">
         <div style="width:150px">
             @component('dashboard.components.formElements.input', [
                 'type' => 'text',
                 'id' => 'anggaran-digunakan-' . $dataKey,
                 'name' => 'anggaran-digunakan-' . $dataKey,
                 'class' => 'uang anggaran-digunakan',
                 'placeholder' => 'Masukkan Anggaran Digunakan',
                 'dataKey' => $dataKey,
                 'value' => '',
             ])
             @endcomponent
         </div>
     </td>
     <td class="align-middle" id="sisa-anggaran-{{ $dataKey }}">
         0
     </td>
     <td class="text-center">
         <button type="button" class="btn btn-danger btn-sm btn-delete-program-kegiatan"
             data-key="{{ $dataKey }}"><i class="far fa-trash-alt"></i>
             Hapus</button>
     </td>
 </tr>
