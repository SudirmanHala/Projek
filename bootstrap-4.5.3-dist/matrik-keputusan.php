<div class="card">
                <div class="card-header">
                    <h3>Matriks Keputusan (X)</h3>
                    <hr class="bg-primary">
                </div>
                    <div class="card-body">
                         
                        <table class="table table-striped table-hover table-bordered"   >
                            <thead class="table-primary">
                                <tr class="super-top">
                                    <th scope="col" rowspan="2" class="super-top-left">No. alternatif</th>
                                    <th scope="col" colspan="<?php echo count($kriterias); ?>" class="text-xs-center font-weight-bold">Kriteria</th>
                                </tr>
                                <tr>
                                    <?php foreach($kriterias as $kriteria ): ?>
                                        <th scope="col"><?php echo $kriteria['nama']; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($alternatifs as $alternatif): ?>
                                    <tr>
                                        <td><?php echo $alternatif['nama_alternatif']; ?></td>
                                        <?php
                                        // Ambil Nilai
                                        $query3 = $pdo->prepare('SELECT id_kriteria, nilai FROM nilai_alternatif
                                            WHERE id_alternatif = :id_alternatif');
                                        $query3->execute(array(
                                            'id_alternatif' => $alternatif['id_alternatif']
                                        ));			
                                        $query3->setFetchMode(PDO::FETCH_ASSOC);
                                        $nilais = $query3->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
                                        
                                        foreach($kriterias as $id_kriteria => $values):
                                            echo '<td>';
                                            if(isset($nilais[$id_kriteria])) {
                                                echo $nilais[$id_kriteria]['nilai'];
                                                $kriterias[$id_kriteria]['nilai'][$alternatif['id_alternatif']] = $nilais[$id_kriteria]['nilai'];
                                            } else {
                                                echo 0;
                                                $kriterias[$id_kriteria]['nilai'][$alternatif['id_alternatif']] = 0;
                                            }
                                            
                                            if(isset($kriterias[$id_kriteria]['tn_kuadrat'])){
                                                $kriterias[$id_kriteria]['tn_kuadrat'] += pow($kriterias[$id_kriteria]['nilai'][$alternatif['id_alternatif']], 2);
                                            } else {
                                                $kriterias[$id_kriteria]['tn_kuadrat'] = pow($kriterias[$id_kriteria]['nilai'][$alternatif['id_alternatif']], 2);
                                            }
                                            echo '</td>';
                                        endforeach;
                                        ?>
                                        </pre>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Maaf, belum ada data untuk alternatif.</p>
                <?php endif; ?>
            </div>   
            