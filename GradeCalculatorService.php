<?php

/**
 * Service de calcul des moyennes
 * Respecte le principe Single Responsibility
 */
class GradeCalculatorService {
    
    public function calculateAverage(array $grades): float {
        if (empty($grades)) {
            return 0;
        }
        
        $totalPoints = 0;
        $totalCoefficients = 0;
        
        foreach ($grades as $gradeData) {
            $grade = new Grade($gradeData);
            $totalPoints += $grade->getPoints();
            $totalCoefficients += $grade->getCoefficient();
        }
        
        return $totalCoefficients > 0 ? $totalPoints / $totalCoefficients : 0;
    }
    
    public function getGradeStatus(float $note): string {
        if ($note >= 16) return 'excellent';
        if ($note >= 14) return 'bien';
        if ($note >= 12) return 'assez-bien';
        if ($note >= 10) return 'passable';
        return 'insuffisant';
    }
    
    public function getValidatedSubjectsCount(array $grades): int {
        $count = 0;
        foreach ($grades as $gradeData) {
            $grade = new Grade($gradeData);
            if ($grade->isValidated()) {
                $count++;
            }
        }
        return $count;
    }
}
