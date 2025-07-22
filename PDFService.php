<?php

class PDFService {
    
    public function generateTranscript(array $student, array $grades, float $average): string {
        $html = $this->generateTranscriptHTML($student, $grades, $average);
        
        // Ici vous pouvez utiliser une bibliothèque comme TCPDF ou DomPDF
        // Pour la démo, on génère un fichier HTML
        $filename = 'releve_notes_' . $student['matricule'] . '_' . date('Y-m-d') . '.html';
        $filepath = __DIR__ . '/../public/downloads/' . $filename;
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0777, true);
        }
        
        file_put_contents($filepath, $html);
        
        return $filename;
    }
    
    private function generateTranscriptHTML(array $student, array $grades, float $average): string {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Relevé de Notes - ' . htmlspecialchars($student['matricule']) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 40px; }
                .student-info { background: #f8f9fa; padding: 20px; margin-bottom: 30px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                th { background-color: #007bff; color: white; }
                .average { background: #28a745; color: white; font-weight: bold; }
                .footer { margin-top: 40px; text-align: center; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>🎓 RELEVÉ DE NOTES OFFICIEL</h1>
                <h2>EduGrades - Système de Gestion Académique</h2>
                <p>Année académique 2024-2025</p>
            </div>
            
            <div class="student-info">
                <h3>📋 Informations de l\'étudiant</h3>
                <p><strong>Matricule:</strong> ' . htmlspecialchars($student['matricule']) . '</p>
                <p><strong>Nom:</strong> ' . htmlspecialchars($student['nom']) . '</p>
                <p><strong>Prénom:</strong> ' . htmlspecialchars($student['prenom']) . '</p>
                <p><strong>Formation:</strong> ' . htmlspecialchars($student['formation_libelle']) . '</p>
                <p><strong>Date d\'édition:</strong> ' . date('d/m/Y H:i') . '</p>
            </div>
            
            <h3>📊 Détail des notes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Matière</th>
                        <th>Note</th>
                        <th>Coefficient</th>
                        <th>Points</th>
                        <th>Mention</th>
                    </tr>
                </thead>
                <tbody>';
        
        $totalPoints = 0;
        $totalCoefficients = 0;
        
        foreach ($grades as $grade) {
            $points = $grade['note'] * $grade['coefficient'];
            $totalPoints += $points;
            $totalCoefficients += $grade['coefficient'];
            
            $mention = '';
            if ($grade['note'] >= 16) $mention = '🌟 Très Bien';
            elseif ($grade['note'] >= 14) $mention = '⭐ Bien';
            elseif ($grade['note'] >= 12) $mention = '👍 Assez Bien';
            elseif ($grade['note'] >= 10) $mention = '✅ Passable';
            else $mention = '❌ Insuffisant';
            
            $html .= '
                    <tr>
                        <td>' . htmlspecialchars($grade['matiere_code']) . '</td>
                        <td>' . htmlspecialchars($grade['matiere_libelle']) . '</td>
                        <td>' . $grade['note'] . '/20</td>
                        <td>' . $grade['coefficient'] . '</td>
                        <td>' . number_format($points, 2) . '</td>
                        <td>' . $mention . '</td>
                    </tr>';
        }
        
        $html .= '
                    <tr class="average">
                        <td colspan="4"><strong>MOYENNE GÉNÉRALE</strong></td>
                        <td><strong>' . number_format($totalPoints, 2) . '</strong></td>
                        <td><strong>' . number_format($average, 2) . '/20</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="footer">
                <p>Document généré automatiquement par EduGrades</p>
                <p>© 2024 - Système de Gestion des Notes Étudiantes</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
