<?php

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
