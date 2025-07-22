<?php

class PDFService {
    
    public function generateTranscript(array $student, array $grades, float $average): string {
        $html = $this->generateTranscriptHTML($student, $grades, $average);
        
        $filename = 'releve_notes_' . $student['matricule'] . '_' . date('Y-m-d') . '.html';
        $filepath = __DIR__ . '/../../public/downloads/' . $filename;
        
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0777, true);
        }
        
        file_put_contents($filepath, $html);
        
        return $filename;
    }
    
    private function generateTranscriptHTML(array $student, array $grades, float $average): string {
        $html = '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Relevé de Notes - ' . htmlspecialchars($student['matricule']) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
                .container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
                .header { text-align: center; margin-bottom: 40px; color: #333; }
                .header h1 { color: #667eea; font-size: 2.5rem; margin-bottom: 10px; }
                .student-info { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 25px; border-radius: 10px; margin-bottom: 30px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 30px; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
                th { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; text-align: left; }
                td { padding: 12px 15px; border-bottom: 1px solid #eee; }
                tr:nth-child(even) { background: #f8f9fa; }
                .average { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; font-weight: bold; }
                .excellent { color: #28a745; font-weight: bold; }
                .bien { color: #17a2b8; font-weight: bold; }
                .passable { color: #ffc107; font-weight: bold; }
                .insuffisant { color: #dc3545; font-weight: bold; }
                .footer { margin-top: 40px; text-align: center; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
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
            
            $mentionClass = '';
            $mention = '';
            if ($grade['note'] >= 16) {
                $mention = '🌟 Excellent';
                $mentionClass = 'excellent';
            } elseif ($grade['note'] >= 14) {
                $mention = '⭐ Bien';
                $mentionClass = 'bien';
            } elseif ($grade['note'] >= 12) {
                $mention = '👍 Assez Bien';
                $mentionClass = 'bien';
            } elseif ($grade['note'] >= 10) {
                $mention = '✅ Passable';
                $mentionClass = 'passable';
            } else {
                $mention = '❌ Insuffisant';
                $mentionClass = 'insuffisant';
            }
            
            $html .= '<tr>
                        <td>' . htmlspecialchars($grade['matiere_code']) . '</td>
                        <td>' . htmlspecialchars($grade['matiere_libelle']) . '</td>
                        <td><strong>' . $grade['note'] . '/20</strong></td>
                        <td>' . $grade['coefficient'] . '</td>
                        <td>' . number_format($points, 2) . '</td>
                        <td class="' . $mentionClass . '">' . $mention . '</td>
                    </tr>';
        }
        
        $html .= '<tr class="average">
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
        </div>
        </body>
        </html>';
        
        return $html;
    }
}
