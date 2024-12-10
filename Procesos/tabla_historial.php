<?php if (count($result_history) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Sala</th>
                <th>Mesa</th>
                <th>Camarero</th>
                <th>Fecha ocupación</th>
                <th>Cliente asignado</th>
                <th>Fecha desocupación</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($result_history as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name_sala']); ?></td>
                <td>Mesa <?php echo htmlspecialchars($row['id_mesa']); ?> (<?php echo htmlspecialchars($row['n_asientos']); ?> asientos)</td>
                <td><?php echo htmlspecialchars($row['name'] . " " . $row['surname']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_C']); ?></td>
                <td><?php echo htmlspecialchars($row['assigned_to']); ?></td>
                <td><?php echo $row['fecha_F'] ? htmlspecialchars($row['fecha_F']) : 'N/A'; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay historial disponible.</p>
<?php endif; ?>
