"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import {
  Users,
  BookOpen,
  TrendingUp,
  Award,
  Search,
  Plus,
  Edit,
  Trash2,
  Download,
  Eye,
  LogOut,
  Filter,
  BarChart3,
  FileText,
  GraduationCap,
} from "lucide-react"
import { useToast } from "@/hooks/use-toast"

// Types
interface Student {
  id: number
  nom: string
  prenom: string
  email: string
  formation: string
  niveau: string
  status: "actif" | "inactif"
}

interface Grade {
  id: number
  studentId: number
  matiere: string
  note: number
  coefficient: number
  type: "DS" | "Examen" | "TP" | "Projet"
  date: string
}

interface Formation {
  id: number
  nom: string
  niveau: string
  matieres: string[]
}

export default function AdminDashboard() {
  const router = useRouter()
  const { toast } = useToast()

  // États
  const [students, setStudents] = useState<Student[]>([])
  const [grades, setGrades] = useState<Grade[]>([])
  const [formations, setFormations] = useState<Formation[]>([])
  const [searchTerm, setSearchTerm] = useState("")
  const [selectedFormation, setSelectedFormation] = useState("all")
  const [selectedStudent, setSelectedStudent] = useState<Student | null>(null)
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false)
  const [isAddGradeDialogOpen, setIsAddGradeDialogOpen] = useState(false)
  const [isViewGradesDialogOpen, setIsViewGradesDialogOpen] = useState(false)

  // Formulaires
  const [editForm, setEditForm] = useState({
    nom: "",
    prenom: "",
    email: "",
    formation: "",
    niveau: "",
  })

  const [gradeForm, setGradeForm] = useState({
    matiere: "",
    note: "",
    coefficient: "1",
    type: "DS" as "DS" | "Examen" | "TP" | "Projet",
  })

  // Données initiales
  useEffect(() => {
    const initialFormations: Formation[] = [
      {
        id: 1,
        nom: "Informatique",
        niveau: "Licence",
        matieres: ["Programmation", "Base de données", "Réseaux", "Algorithmes"],
      },
      {
        id: 2,
        nom: "Mathématiques",
        niveau: "Master",
        matieres: ["Analyse", "Algèbre", "Statistiques", "Probabilités"],
      },
      {
        id: 3,
        nom: "Physique",
        niveau: "Licence",
        matieres: ["Mécanique", "Thermodynamique", "Électricité", "Optique"],
      },
    ]

    const initialStudents: Student[] = [
      {
        id: 1,
        nom: "Diop",
        prenom: "Amadou",
        email: "amadou.diop@email.com",
        formation: "Informatique",
        niveau: "Licence",
        status: "actif",
      },
      {
        id: 2,
        nom: "Fall",
        prenom: "Fatou",
        email: "fatou.fall@email.com",
        formation: "Mathématiques",
        niveau: "Master",
        status: "actif",
      },
      {
        id: 3,
        nom: "Ndiaye",
        prenom: "Moussa",
        email: "moussa.ndiaye@email.com",
        formation: "Physique",
        niveau: "Licence",
        status: "actif",
      },
      {
        id: 4,
        nom: "Sow",
        prenom: "Aïcha",
        email: "aicha.sow@email.com",
        formation: "Informatique",
        niveau: "Licence",
        status: "inactif",
      },
    ]

    const initialGrades: Grade[] = [
      { id: 1, studentId: 1, matiere: "Programmation", note: 16, coefficient: 2, type: "Examen", date: "2024-01-15" },
      { id: 2, studentId: 1, matiere: "Base de données", note: 14, coefficient: 1, type: "DS", date: "2024-01-20" },
      { id: 3, studentId: 2, matiere: "Analyse", note: 18, coefficient: 3, type: "Examen", date: "2024-01-18" },
      { id: 4, studentId: 2, matiere: "Algèbre", note: 15, coefficient: 2, type: "DS", date: "2024-01-22" },
      { id: 5, studentId: 3, matiere: "Mécanique", note: 12, coefficient: 2, type: "TP", date: "2024-01-25" },
      { id: 6, studentId: 1, matiere: "Algorithmes", note: 17, coefficient: 2, type: "Projet", date: "2024-01-28" },
    ]

    setFormations(initialFormations)
    setStudents(initialStudents)
    setGrades(initialGrades)
  }, [])

  // Fonctions utilitaires
  const getStudentGrades = (studentId: number) => {
    return grades.filter((grade) => grade.studentId === studentId)
  }

  const calculateAverage = (studentId: number) => {
    const studentGrades = getStudentGrades(studentId)
    if (studentGrades.length === 0) return 0

    const totalPoints = studentGrades.reduce((sum, grade) => sum + grade.note * grade.coefficient, 0)
    const totalCoefficients = studentGrades.reduce((sum, grade) => sum + grade.coefficient, 0)

    return totalCoefficients > 0 ? totalPoints / totalCoefficients : 0
  }

  // Filtrage des étudiants
  const filteredStudents = students.filter((student) => {
    const matchesSearch =
      student.nom.toLowerCase().includes(searchTerm.toLowerCase()) ||
      student.prenom.toLowerCase().includes(searchTerm.toLowerCase()) ||
      student.email.toLowerCase().includes(searchTerm.toLowerCase())
    const matchesFormation = selectedFormation === "all" || student.formation === selectedFormation
    return matchesSearch && matchesFormation
  })

  // Statistiques
  const stats = {
    totalStudents: students.length,
    activeStudents: students.filter((s) => s.status === "actif").length,
    totalFormations: formations.length,
    averageGrade: grades.length > 0 ? grades.reduce((sum, grade) => sum + grade.note, 0) / grades.length : 0,
  }

  // Handlers
  const handleLogout = () => {
    toast({
      title: "Déconnexion",
      description: "À bientôt !",
    })
    router.push("/")
  }

  const handleEditStudent = (student: Student) => {
    setSelectedStudent(student)
    setEditForm({
      nom: student.nom,
      prenom: student.prenom,
      email: student.email,
      formation: student.formation,
      niveau: student.niveau,
    })
    setIsEditDialogOpen(true)
  }

  const handleSaveStudent = () => {
    if (selectedStudent) {
      setStudents((prev) =>
        prev.map((student) => (student.id === selectedStudent.id ? { ...student, ...editForm } : student)),
      )
      toast({
        title: "Étudiant modifié",
        description: "Les informations ont été mises à jour avec succès.",
      })
      setIsEditDialogOpen(false)
    }
  }

  const handleDeleteStudent = (studentId: number) => {
    setStudents((prev) => prev.filter((student) => student.id !== studentId))
    setGrades((prev) => prev.filter((grade) => grade.studentId !== studentId))
    toast({
      title: "Étudiant supprimé",
      description: "L'étudiant et ses notes ont été supprimés.",
    })
  }

  const handleAddGrade = () => {
    if (selectedStudent && gradeForm.matiere && gradeForm.note) {
      const newGrade: Grade = {
        id: grades.length + 1,
        studentId: selectedStudent.id,
        matiere: gradeForm.matiere,
        note: Number.parseFloat(gradeForm.note),
        coefficient: Number.parseInt(gradeForm.coefficient),
        type: gradeForm.type,
        date: new Date().toISOString().split("T")[0],
      }

      setGrades((prev) => [...prev, newGrade])
      toast({
        title: "Note ajoutée",
        description: `Note de ${gradeForm.note}/20 ajoutée pour ${gradeForm.matiere}`,
      })

      setGradeForm({ matiere: "", note: "", coefficient: "1", type: "DS" })
      setIsAddGradeDialogOpen(false)
    }
  }

  const handleDownloadGrades = () => {
    const csvContent = [
      ["Nom", "Prénom", "Formation", "Matière", "Note", "Coefficient", "Type", "Date"].join(","),
      ...grades.map((grade) => {
        const student = students.find((s) => s.id === grade.studentId)
        return [
          student?.nom || "",
          student?.prenom || "",
          student?.formation || "",
          grade.matiere,
          grade.note,
          grade.coefficient,
          grade.type,
          grade.date,
        ].join(",")
      }),
    ].join("\n")

    const blob = new Blob([csvContent], { type: "text/csv" })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement("a")
    a.href = url
    a.download = "notes_etudiants.csv"
    a.click()
    window.URL.revokeObjectURL(url)

    toast({
      title: "Export réussi",
      description: "Le fichier CSV a été téléchargé.",
    })
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-beige-50 via-white to-tan-50">
      {/* Header */}
      <header className="bg-white shadow-sm border-b border-beige-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center space-x-3">
              <div className="bg-brown-700 p-2 rounded-lg shadow-md">
                <GraduationCap className="h-6 w-6 text-white" />
              </div>
              <div>
                <h1 className="text-xl font-bold text-brown-800">Sama-Note</h1>
                <p className="text-sm text-brown-600">Dashboard Administrateur</p>
              </div>
            </div>
            <Button
              variant="outline"
              onClick={handleLogout}
              className="flex items-center space-x-2 border-beige-300 hover:bg-beige-100 text-brown-700 bg-transparent"
            >
              <LogOut className="h-4 w-4" />
              <span>Déconnexion</span>
            </Button>
          </div>
        </div>
      </header>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Statistiques */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <Card className="hover-lift border-beige-200 bg-white">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-brown-600">Total Étudiants</p>
                  <p className="text-3xl font-bold text-brown-700">{stats.totalStudents}</p>
                </div>
                <Users className="h-8 w-8 text-brown-600" />
              </div>
            </CardContent>
          </Card>

          <Card className="hover-lift border-beige-200 bg-white">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-brown-600">Étudiants Actifs</p>
                  <p className="text-3xl font-bold text-green-600">{stats.activeStudents}</p>
                </div>
                <TrendingUp className="h-8 w-8 text-green-600" />
              </div>
            </CardContent>
          </Card>

          <Card className="hover-lift border-beige-200 bg-white">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-brown-600">Formations</p>
                  <p className="text-3xl font-bold text-tan-600">{stats.totalFormations}</p>
                </div>
                <BookOpen className="h-8 w-8 text-tan-600" />
              </div>
            </CardContent>
          </Card>

          <Card className="hover-lift border-beige-200 bg-white">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-brown-600">Moyenne Générale</p>
                  <p className="text-3xl font-bold text-brown-700">{stats.averageGrade.toFixed(1)}/20</p>
                </div>
                <Award className="h-8 w-8 text-brown-700" />
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Contenu principal */}
        <Tabs defaultValue="students" className="space-y-6">
          <TabsList className="grid w-full grid-cols-2 bg-beige-100">
            <TabsTrigger value="students" className="data-[state=active]:bg-brown-700 data-[state=active]:text-white">
              Gestion des Étudiants
            </TabsTrigger>
            <TabsTrigger value="analytics" className="data-[state=active]:bg-brown-700 data-[state=active]:text-white">
              Analyses et Rapports
            </TabsTrigger>
          </TabsList>

          {/* Onglet Étudiants */}
          <TabsContent value="students" className="space-y-6">
            <Card className="border-beige-200 bg-white">
              <CardHeader>
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                  <div>
                    <CardTitle className="text-xl text-brown-800">Liste des Étudiants</CardTitle>
                    <CardDescription className="text-brown-600">Gérez les étudiants et leurs notes</CardDescription>
                  </div>
                  <div className="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <Button
                      onClick={handleDownloadGrades}
                      variant="outline"
                      size="sm"
                      className="border-beige-300 hover:bg-beige-100 text-brown-700 bg-transparent"
                    >
                      <Download className="h-4 w-4 mr-2" />
                      Exporter Notes
                    </Button>
                  </div>
                </div>
              </CardHeader>
              <CardContent>
                {/* Filtres */}
                <div className="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 mb-6">
                  <div className="relative flex-1">
                    <Search className="absolute left-3 top-3 h-4 w-4 text-brown-400" />
                    <Input
                      placeholder="Rechercher un étudiant..."
                      value={searchTerm}
                      onChange={(e) => setSearchTerm(e.target.value)}
                      className="pl-10 border-beige-300 focus:border-brown-500"
                    />
                  </div>
                  <Select value={selectedFormation} onValueChange={setSelectedFormation}>
                    <SelectTrigger className="w-full sm:w-48 border-beige-300 focus:border-brown-500">
                      <Filter className="h-4 w-4 mr-2" />
                      <SelectValue placeholder="Formation" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="all">Toutes les formations</SelectItem>
                      {formations.map((formation) => (
                        <SelectItem key={formation.id} value={formation.nom}>
                          {formation.nom}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>

                {/* Tableau des étudiants */}
                <div className="rounded-md border border-beige-200">
                  <Table>
                    <TableHeader>
                      <TableRow className="bg-beige-50">
                        <TableHead className="text-brown-700">Nom Complet</TableHead>
                        <TableHead className="text-brown-700">Email</TableHead>
                        <TableHead className="text-brown-700">Formation</TableHead>
                        <TableHead className="text-brown-700">Niveau</TableHead>
                        <TableHead className="text-brown-700">Moyenne</TableHead>
                        <TableHead className="text-brown-700">Statut</TableHead>
                        <TableHead className="text-right text-brown-700">Actions</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {filteredStudents.map((student) => (
                        <TableRow key={student.id} className="hover:bg-beige-50">
                          <TableCell className="font-medium text-brown-800">
                            {student.prenom} {student.nom}
                          </TableCell>
                          <TableCell className="text-brown-600">{student.email}</TableCell>
                          <TableCell className="text-brown-600">{student.formation}</TableCell>
                          <TableCell className="text-brown-600">{student.niveau}</TableCell>
                          <TableCell>
                            <Badge
                              variant={calculateAverage(student.id) >= 10 ? "default" : "destructive"}
                              className={calculateAverage(student.id) >= 10 ? "bg-brown-700" : ""}
                            >
                              {calculateAverage(student.id).toFixed(1)}/20
                            </Badge>
                          </TableCell>
                          <TableCell>
                            <Badge
                              variant={student.status === "actif" ? "default" : "secondary"}
                              className={student.status === "actif" ? "bg-green-600" : "bg-gray-500"}
                            >
                              {student.status}
                            </Badge>
                          </TableCell>
                          <TableCell className="text-right">
                            <div className="flex justify-end space-x-2">
                              <Button
                                variant="outline"
                                size="sm"
                                className="border-beige-300 hover:bg-beige-100 bg-transparent"
                                onClick={() => {
                                  setSelectedStudent(student)
                                  setIsViewGradesDialogOpen(true)
                                }}
                              >
                                <Eye className="h-4 w-4" />
                              </Button>
                              <Button
                                variant="outline"
                                size="sm"
                                className="border-beige-300 hover:bg-beige-100 bg-transparent"
                                onClick={() => handleEditStudent(student)}
                              >
                                <Edit className="h-4 w-4" />
                              </Button>
                              <Button
                                variant="outline"
                                size="sm"
                                className="border-beige-300 hover:bg-beige-100 bg-transparent"
                                onClick={() => {
                                  setSelectedStudent(student)
                                  setIsAddGradeDialogOpen(true)
                                }}
                              >
                                <Plus className="h-4 w-4" />
                              </Button>
                              <Button
                                variant="outline"
                                size="sm"
                                className="border-red-300 hover:bg-red-50 text-red-600 bg-transparent"
                                onClick={() => handleDeleteStudent(student.id)}
                              >
                                <Trash2 className="h-4 w-4" />
                              </Button>
                            </div>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Onglet Analytics */}
          <TabsContent value="analytics" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <Card className="border-beige-200 bg-white">
                <CardHeader>
                  <CardTitle className="flex items-center space-x-2 text-brown-800">
                    <BarChart3 className="h-5 w-5" />
                    <span>Répartition par Formation</span>
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    {formations.map((formation) => {
                      const count = students.filter((s) => s.formation === formation.nom).length
                      const percentage = students.length > 0 ? (count / students.length) * 100 : 0
                      return (
                        <div key={formation.id} className="space-y-2">
                          <div className="flex justify-between text-sm">
                            <span className="text-brown-700">{formation.nom}</span>
                            <span className="text-brown-600">
                              {count} étudiants ({percentage.toFixed(1)}%)
                            </span>
                          </div>
                          <div className="w-full bg-beige-200 rounded-full h-2">
                            <div className="bg-brown-600 h-2 rounded-full" style={{ width: `${percentage}%` }}></div>
                          </div>
                        </div>
                      )
                    })}
                  </div>
                </CardContent>
              </Card>

              <Card className="border-beige-200 bg-white">
                <CardHeader>
                  <CardTitle className="flex items-center space-x-2 text-brown-800">
                    <FileText className="h-5 w-5" />
                    <span>Statistiques des Notes</span>
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    <div className="flex justify-between items-center p-3 bg-green-50 rounded-lg border border-green-200">
                      <span className="text-sm font-medium text-green-800">Notes ≥ 16</span>
                      <Badge className="bg-green-600">{grades.filter((g) => g.note >= 16).length}</Badge>
                    </div>
                    <div className="flex justify-between items-center p-3 bg-beige-100 rounded-lg border border-beige-300">
                      <span className="text-sm font-medium text-brown-700">Notes 10-15</span>
                      <Badge className="bg-brown-600">{grades.filter((g) => g.note >= 10 && g.note < 16).length}</Badge>
                    </div>
                    <div className="flex justify-between items-center p-3 bg-red-50 rounded-lg border border-red-200">
                      <span className="text-sm font-medium text-red-800">Notes &lt; 10</span>
                      <Badge className="bg-red-600">{grades.filter((g) => g.note < 10).length}</Badge>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>
        </Tabs>
      </div>

      {/* Dialog Modifier Étudiant */}
      <Dialog open={isEditDialogOpen} onOpenChange={setIsEditDialogOpen}>
        <DialogContent className="bg-white border-beige-200">
          <DialogHeader>
            <DialogTitle className="text-brown-800">Modifier l'étudiant</DialogTitle>
            <DialogDescription className="text-brown-600">Modifiez les informations de l'étudiant</DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            <div className="grid grid-cols-2 gap-4">
              <div>
                <Label htmlFor="nom" className="text-brown-700">
                  Nom
                </Label>
                <Input
                  id="nom"
                  value={editForm.nom}
                  onChange={(e) => setEditForm((prev) => ({ ...prev, nom: e.target.value }))}
                  className="border-beige-300 focus:border-brown-500"
                />
              </div>
              <div>
                <Label htmlFor="prenom" className="text-brown-700">
                  Prénom
                </Label>
                <Input
                  id="prenom"
                  value={editForm.prenom}
                  onChange={(e) => setEditForm((prev) => ({ ...prev, prenom: e.target.value }))}
                  className="border-beige-300 focus:border-brown-500"
                />
              </div>
            </div>
            <div>
              <Label htmlFor="email" className="text-brown-700">
                Email
              </Label>
              <Input
                id="email"
                type="email"
                value={editForm.email}
                onChange={(e) => setEditForm((prev) => ({ ...prev, email: e.target.value }))}
                className="border-beige-300 focus:border-brown-500"
              />
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div>
                <Label htmlFor="formation" className="text-brown-700">
                  Formation
                </Label>
                <Select
                  value={editForm.formation}
                  onValueChange={(value) => setEditForm((prev) => ({ ...prev, formation: value }))}
                >
                  <SelectTrigger className="border-beige-300 focus:border-brown-500">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    {formations.map((formation) => (
                      <SelectItem key={formation.id} value={formation.nom}>
                        {formation.nom}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              <div>
                <Label htmlFor="niveau" className="text-brown-700">
                  Niveau
                </Label>
                <Select
                  value={editForm.niveau}
                  onValueChange={(value) => setEditForm((prev) => ({ ...prev, niveau: value }))}
                >
                  <SelectTrigger className="border-beige-300 focus:border-brown-500">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="Licence">Licence</SelectItem>
                    <SelectItem value="Master">Master</SelectItem>
                    <SelectItem value="Doctorat">Doctorat</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
          </div>
          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setIsEditDialogOpen(false)}
              className="border-beige-300 hover:bg-beige-100"
            >
              Annuler
            </Button>
            <Button onClick={handleSaveStudent} className="bg-brown-700 hover:bg-brown-800 text-white">
              Sauvegarder
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Dialog Ajouter Note */}
      <Dialog open={isAddGradeDialogOpen} onOpenChange={setIsAddGradeDialogOpen}>
        <DialogContent className="bg-white border-beige-200">
          <DialogHeader>
            <DialogTitle className="text-brown-800">Ajouter une note</DialogTitle>
            <DialogDescription className="text-brown-600">
              Ajouter une nouvelle note pour {selectedStudent?.prenom} {selectedStudent?.nom}
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            <div>
              <Label htmlFor="matiere" className="text-brown-700">
                Matière
              </Label>
              <Select
                value={gradeForm.matiere}
                onValueChange={(value) => setGradeForm((prev) => ({ ...prev, matiere: value }))}
              >
                <SelectTrigger className="border-beige-300 focus:border-brown-500">
                  <SelectValue placeholder="Sélectionner une matière" />
                </SelectTrigger>
                <SelectContent>
                  {selectedStudent &&
                    formations
                      .find((f) => f.nom === selectedStudent.formation)
                      ?.matieres.map((matiere) => (
                        <SelectItem key={matiere} value={matiere}>
                          {matiere}
                        </SelectItem>
                      ))}
                </SelectContent>
              </Select>
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div>
                <Label htmlFor="note" className="text-brown-700">
                  Note (/20)
                </Label>
                <Input
                  id="note"
                  type="number"
                  min="0"
                  max="20"
                  step="0.5"
                  value={gradeForm.note}
                  onChange={(e) => setGradeForm((prev) => ({ ...prev, note: e.target.value }))}
                  className="border-beige-300 focus:border-brown-500"
                />
              </div>
              <div>
                <Label htmlFor="coefficient" className="text-brown-700">
                  Coefficient
                </Label>
                <Input
                  id="coefficient"
                  type="number"
                  min="1"
                  max="5"
                  value={gradeForm.coefficient}
                  onChange={(e) => setGradeForm((prev) => ({ ...prev, coefficient: e.target.value }))}
                  className="border-beige-300 focus:border-brown-500"
                />
              </div>
            </div>
            <div>
              <Label htmlFor="type" className="text-brown-700">
                Type d'évaluation
              </Label>
              <Select
                value={gradeForm.type}
                onValueChange={(value: "DS" | "Examen" | "TP" | "Projet") =>
                  setGradeForm((prev) => ({ ...prev, type: value }))
                }
              >
                <SelectTrigger className="border-beige-300 focus:border-brown-500">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="DS">Devoir Surveillé</SelectItem>
                  <SelectItem value="Examen">Examen</SelectItem>
                  <SelectItem value="TP">Travaux Pratiques</SelectItem>
                  <SelectItem value="Projet">Projet</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setIsAddGradeDialogOpen(false)}
              className="border-beige-300 hover:bg-beige-100"
            >
              Annuler
            </Button>
            <Button onClick={handleAddGrade} className="bg-brown-700 hover:bg-brown-800 text-white">
              Ajouter la note
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Dialog Voir Notes */}
      <Dialog open={isViewGradesDialogOpen} onOpenChange={setIsViewGradesDialogOpen}>
        <DialogContent className="max-w-4xl bg-white border-beige-200">
          <DialogHeader>
            <DialogTitle className="text-brown-800">
              Notes de {selectedStudent?.prenom} {selectedStudent?.nom}
            </DialogTitle>
            <DialogDescription className="text-brown-600">
              Formation: {selectedStudent?.formation} - Niveau: {selectedStudent?.niveau}
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            {selectedStudent && (
              <>
                <div className="flex justify-between items-center p-4 bg-beige-100 rounded-lg border border-beige-300">
                  <span className="font-medium text-brown-700">Moyenne générale</span>
                  <Badge className="bg-brown-700 text-white text-lg px-3 py-1">
                    {calculateAverage(selectedStudent.id).toFixed(2)}/20
                  </Badge>
                </div>
                <div className="rounded-md border border-beige-200">
                  <Table>
                    <TableHeader>
                      <TableRow className="bg-beige-50">
                        <TableHead className="text-brown-700">Matière</TableHead>
                        <TableHead className="text-brown-700">Note</TableHead>
                        <TableHead className="text-brown-700">Coefficient</TableHead>
                        <TableHead className="text-brown-700">Type</TableHead>
                        <TableHead className="text-brown-700">Date</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {getStudentGrades(selectedStudent.id).map((grade) => (
                        <TableRow key={grade.id} className="hover:bg-beige-50">
                          <TableCell className="font-medium text-brown-800">{grade.matiere}</TableCell>
                          <TableCell>
                            <Badge
                              variant={grade.note >= 10 ? "default" : "destructive"}
                              className={grade.note >= 10 ? "bg-brown-700" : ""}
                            >
                              {grade.note}/20
                            </Badge>
                          </TableCell>
                          <TableCell className="text-brown-600">{grade.coefficient}</TableCell>
                          <TableCell className="text-brown-600">{grade.type}</TableCell>
                          <TableCell className="text-brown-600">
                            {new Date(grade.date).toLocaleDateString("fr-FR")}
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </>
            )}
          </div>
          <DialogFooter>
            <Button
              onClick={() => setIsViewGradesDialogOpen(false)}
              className="bg-brown-700 hover:bg-brown-800 text-white"
            >
              Fermer
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  )
}
