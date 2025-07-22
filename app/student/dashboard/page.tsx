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
import { Switch } from "@/components/ui/switch"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import {
  BookOpen,
  TrendingUp,
  Award,
  Download,
  LogOut,
  Settings,
  Bell,
  User,
  Mail,
  Calendar,
  GraduationCap,
  BarChart3,
  FileText,
} from "lucide-react"
import { useToast } from "@/hooks/use-toast"

// Types
interface Grade {
  id: number
  matiere: string
  note: number
  coefficient: number
  type: "DS" | "Examen" | "TP" | "Projet"
  date: string
}

interface StudentInfo {
  nom: string
  prenom: string
  email: string
  formation: string
  niveau: string
  dateInscription: string
}

export default function StudentDashboard() {
  const router = useRouter()
  const { toast } = useToast()

  // États
  const [grades, setGrades] = useState<Grade[]>([])
  const [studentInfo, setStudentInfo] = useState<StudentInfo>({
    nom: "Diop",
    prenom: "Amadou",
    email: "amadou.diop@email.com",
    formation: "Informatique",
    niveau: "Licence",
    dateInscription: "2023-09-15",
  })

  // États pour les paramètres
  const [isPasswordDialogOpen, setIsPasswordDialogOpen] = useState(false)
  const [passwordForm, setPasswordForm] = useState({
    newPassword: "",
    confirmPassword: "",
  })
  const [preferences, setPreferences] = useState({
    emailNotifications: true,
    examReminders: true,
    darkMode: false,
  })

  // Données initiales
  useEffect(() => {
    const initialGrades: Grade[] = [
      { id: 1, matiere: "Programmation", note: 16, coefficient: 2, type: "Examen", date: "2024-01-15" },
      { id: 2, matiere: "Base de données", note: 14, coefficient: 1, type: "DS", date: "2024-01-20" },
      { id: 3, matiere: "Algorithmes", note: 17, coefficient: 2, type: "Projet", date: "2024-01-28" },
      { id: 4, matiere: "Réseaux", note: 13, coefficient: 1, type: "TP", date: "2024-02-05" },
      { id: 5, matiere: "Programmation", note: 15, coefficient: 1, type: "DS", date: "2024-02-10" },
      { id: 6, matiere: "Base de données", note: 18, coefficient: 2, type: "Examen", date: "2024-02-15" },
    ]
    setGrades(initialGrades)
  }, [])

  // Calculs
  const calculateAverage = () => {
    if (grades.length === 0) return 0
    const totalPoints = grades.reduce((sum, grade) => sum + grade.note * grade.coefficient, 0)
    const totalCoefficients = grades.reduce((sum, grade) => sum + grade.coefficient, 0)
    return totalCoefficients > 0 ? totalPoints / totalCoefficients : 0
  }

  const getSubjectAverage = (subject: string) => {
    const subjectGrades = grades.filter((grade) => grade.matiere === subject)
    if (subjectGrades.length === 0) return 0
    const totalPoints = subjectGrades.reduce((sum, grade) => sum + grade.note * grade.coefficient, 0)
    const totalCoefficients = subjectGrades.reduce((sum, grade) => sum + grade.coefficient, 0)
    return totalCoefficients > 0 ? totalPoints / totalCoefficients : 0
  }

  const getUniqueSubjects = () => {
    return [...new Set(grades.map((grade) => grade.matiere))]
  }

  const stats = {
    totalGrades: grades.length,
    average: calculateAverage(),
    bestGrade: grades.length > 0 ? Math.max(...grades.map((g) => g.note)) : 0,
    subjects: getUniqueSubjects().length,
  }

  // Handlers
  const handleLogout = () => {
    toast({
      title: "Déconnexion",
      description: "À bientôt !",
    })
    router.push("/")
  }

  const handlePasswordChange = () => {
    if (passwordForm.newPassword !== passwordForm.confirmPassword) {
      toast({
        title: "Erreur",
        description: "Les mots de passe ne correspondent pas",
        variant: "destructive",
      })
      return
    }

    if (passwordForm.newPassword.length < 6) {
      toast({
        title: "Erreur",
        description: "Le mot de passe doit contenir au moins 6 caractères",
        variant: "destructive",
      })
      return
    }

    toast({
      title: "Mot de passe modifié",
      description: "Votre mot de passe a été mis à jour avec succès",
    })
    setPasswordForm({ newPassword: "", confirmPassword: "" })
    setIsPasswordDialogOpen(false)
  }

  const handlePreferenceChange = (key: keyof typeof preferences) => {
    setPreferences((prev) => ({ ...prev, [key]: !prev[key] }))
    toast({
      title: "Préférence mise à jour",
      description: "Vos paramètres ont été sauvegardés",
    })
  }

  const handleDownloadTranscript = () => {
    const csvContent = [
      ["Matière", "Note", "Coefficient", "Type", "Date"].join(","),
      ...grades.map((grade) => [grade.matiere, grade.note, grade.coefficient, grade.type, grade.date].join(",")),
    ].join("\n")

    const blob = new Blob([csvContent], { type: "text/csv" })
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement("a")
    a.href = url
    a.download = `releve_notes_${studentInfo.nom}_${studentInfo.prenom}.csv`
    a.click()
    window.URL.revokeObjectURL(url)

    toast({
      title: "Relevé téléchargé",
      description: "Votre relevé de notes a été téléchargé",
    })
  }

  const handleContactSupport = () => {
    toast({
      title: "Message envoyé",
      description: "Votre demande a été transmise au support",
    })
  }

  const handleConnectionHistory = () => {
    toast({
      title: "Historique des connexions",
      description: "Dernière connexion: Aujourd'hui à 14:30",
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
                <p className="text-sm text-brown-600">Espace Étudiant</p>
              </div>
            </div>
            <div className="flex items-center space-x-4">
              <div className="text-right">
                <p className="text-sm font-medium text-brown-800">
                  {studentInfo.prenom} {studentInfo.nom}
                </p>
                <p className="text-xs text-brown-600">{studentInfo.formation}</p>
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
        </div>
      </header>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Statistiques */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <Card className="hover-lift border-beige-200 bg-white">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-brown-600">Moyenne Générale</p>
                  <p className="text-3xl font-bold text-brown-700">{stats.average.toFixed(1)}/20</p>
                </div>
                <Award className="h-8 w-8 text-brown-700" />
              </div>
            </CardContent>
          </Card>

          <Card className="hover-lift border-beige-200 bg-white">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-brown-600">Meilleure Note</p>
                  <p className="text-3xl font-bold text-green-600">{stats.bestGrade}/20</p>
                </div>
                <TrendingUp className="h-8 w-8 text-green-600" />
              </div>
            </CardContent>
          </Card>

          <Card className="hover-lift border-beige-200 bg-white">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-brown-600">Matières</p>
                  <p className="text-3xl font-bold text-tan-600">{stats.subjects}</p>
                </div>
                <BookOpen className="h-8 w-8 text-tan-600" />
              </div>
            </CardContent>
          </Card>

          <Card className="hover-lift border-beige-200 bg-white">
            <CardContent className="p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-brown-600">Total Notes</p>
                  <p className="text-3xl font-bold text-brown-700">{stats.totalGrades}</p>
                </div>
                <FileText className="h-8 w-8 text-brown-700" />
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Contenu principal */}
        <Tabs defaultValue="grades" className="space-y-6">
          <TabsList className="grid w-full grid-cols-3 bg-beige-100">
            <TabsTrigger value="grades" className="data-[state=active]:bg-brown-700 data-[state=active]:text-white">
              Mes Notes
            </TabsTrigger>
            <TabsTrigger value="analytics" className="data-[state=active]:bg-brown-700 data-[state=active]:text-white">
              Analyses
            </TabsTrigger>
            <TabsTrigger value="settings" className="data-[state=active]:bg-brown-700 data-[state=active]:text-white">
              Paramètres
            </TabsTrigger>
          </TabsList>

          {/* Onglet Notes */}
          <TabsContent value="grades" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
              {/* Notes récentes */}
              <div className="lg:col-span-2">
                <Card className="border-beige-200 bg-white">
                  <CardHeader>
                    <div className="flex justify-between items-center">
                      <div>
                        <CardTitle className="text-brown-800">Toutes mes notes</CardTitle>
                        <CardDescription className="text-brown-600">
                          Historique complet de vos évaluations
                        </CardDescription>
                      </div>
                      <Button
                        onClick={handleDownloadTranscript}
                        variant="outline"
                        size="sm"
                        className="border-beige-300 hover:bg-beige-100 text-brown-700 bg-transparent"
                      >
                        <Download className="h-4 w-4 mr-2" />
                        Télécharger
                      </Button>
                    </div>
                  </CardHeader>
                  <CardContent>
                    <div className="rounded-md border border-beige-200">
                      <Table>
                        <TableHeader>
                          <TableRow className="bg-beige-50">
                            <TableHead className="text-brown-700">Matière</TableHead>
                            <TableHead className="text-brown-700">Note</TableHead>
                            <TableHead className="text-brown-700">Coeff.</TableHead>
                            <TableHead className="text-brown-700">Type</TableHead>
                            <TableHead className="text-brown-700">Date</TableHead>
                          </TableRow>
                        </TableHeader>
                        <TableBody>
                          {grades.map((grade) => (
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
                              <TableCell>
                                <Badge variant="outline" className="border-beige-300 text-brown-700">
                                  {grade.type}
                                </Badge>
                              </TableCell>
                              <TableCell className="text-brown-600">
                                {new Date(grade.date).toLocaleDateString("fr-FR")}
                              </TableCell>
                            </TableRow>
                          ))}
                        </TableBody>
                      </Table>
                    </div>
                  </CardContent>
                </Card>
              </div>

              {/* Moyennes par matière */}
              <div>
                <Card className="border-beige-200 bg-white">
                  <CardHeader>
                    <CardTitle className="flex items-center space-x-2 text-brown-800">
                      <BarChart3 className="h-5 w-5" />
                      <span>Moyennes par matière</span>
                    </CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div className="space-y-4">
                      {getUniqueSubjects().map((subject) => {
                        const average = getSubjectAverage(subject)
                        return (
                          <div key={subject} className="space-y-2">
                            <div className="flex justify-between text-sm">
                              <span className="font-medium text-brown-700">{subject}</span>
                              <Badge
                                variant={average >= 10 ? "default" : "destructive"}
                                className={average >= 10 ? "bg-brown-700" : ""}
                              >
                                {average.toFixed(1)}/20
                              </Badge>
                            </div>
                            <div className="w-full bg-beige-200 rounded-full h-2">
                              <div
                                className={`h-2 rounded-full ${average >= 10 ? "bg-brown-600" : "bg-red-500"}`}
                                style={{ width: `${(average / 20) * 100}%` }}
                              ></div>
                            </div>
                          </div>
                        )
                      })}
                    </div>
                  </CardContent>
                </Card>
              </div>
            </div>
          </TabsContent>

          {/* Onglet Analytics */}
          <TabsContent value="analytics" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <Card className="border-beige-200 bg-white">
                <CardHeader>
                  <CardTitle className="text-brown-800">Évolution des notes</CardTitle>
                  <CardDescription className="text-brown-600">Progression au fil du temps</CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    <div className="flex justify-between items-center p-3 bg-green-50 rounded-lg border border-green-200">
                      <span className="text-sm font-medium text-green-800">Notes excellentes (≥16)</span>
                      <Badge className="bg-green-600">{grades.filter((g) => g.note >= 16).length}</Badge>
                    </div>
                    <div className="flex justify-between items-center p-3 bg-beige-100 rounded-lg border border-beige-300">
                      <span className="text-sm font-medium text-brown-700">Notes satisfaisantes (10-15)</span>
                      <Badge className="bg-brown-600">{grades.filter((g) => g.note >= 10 && g.note < 16).length}</Badge>
                    </div>
                    <div className="flex justify-between items-center p-3 bg-red-50 rounded-lg border border-red-200">
                      <span className="text-sm font-medium text-red-800">Notes insuffisantes (&lt;10)</span>
                      <Badge className="bg-red-600">{grades.filter((g) => g.note < 10).length}</Badge>
                    </div>
                  </div>
                </CardContent>
              </Card>

              <Card className="border-beige-200 bg-white">
                <CardHeader>
                  <CardTitle className="text-brown-800">Informations personnelles</CardTitle>
                  <CardDescription className="text-brown-600">Vos données de profil</CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="space-y-3">
                    <div className="flex items-center space-x-3">
                      <User className="h-4 w-4 text-brown-500" />
                      <span className="text-sm text-brown-700">
                        {studentInfo.prenom} {studentInfo.nom}
                      </span>
                    </div>
                    <div className="flex items-center space-x-3">
                      <Mail className="h-4 w-4 text-brown-500" />
                      <span className="text-sm text-brown-700">{studentInfo.email}</span>
                    </div>
                    <div className="flex items-center space-x-3">
                      <BookOpen className="h-4 w-4 text-brown-500" />
                      <span className="text-sm text-brown-700">
                        {studentInfo.formation} - {studentInfo.niveau}
                      </span>
                    </div>
                    <div className="flex items-center space-x-3">
                      <Calendar className="h-4 w-4 text-brown-500" />
                      <span className="text-sm text-brown-700">
                        Inscrit le {new Date(studentInfo.dateInscription).toLocaleDateString("fr-FR")}
                      </span>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          {/* Onglet Paramètres */}
          <TabsContent value="settings" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Paramètres du compte */}
              <Card className="border-beige-200 bg-white">
                <CardHeader>
                  <CardTitle className="flex items-center space-x-2 text-brown-800">
                    <Settings className="h-5 w-5" />
                    <span>Paramètres du compte</span>
                  </CardTitle>
                  <CardDescription className="text-brown-600">Modifier le mot de passe</CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                  <div className="space-y-2">
                    <Label className="text-brown-700">Changez votre mot de passe de connexion</Label>
                    <Button
                      onClick={() => setIsPasswordDialogOpen(true)}
                      className="w-full bg-brown-700 hover:bg-brown-800 text-white"
                    >
                      Modifier le mot de passe
                    </Button>
                  </div>
                </CardContent>
              </Card>

              {/* Préférences */}
              <Card className="border-beige-200 bg-white">
                <CardHeader>
                  <CardTitle className="flex items-center space-x-2 text-brown-800">
                    <Bell className="h-5 w-5" />
                    <span>Préférences</span>
                  </CardTitle>
                  <CardDescription className="text-brown-600">Personnalisez votre expérience Sama-Note</CardDescription>
                </CardHeader>
                <CardContent className="space-y-6">
                  <div className="flex items-center justify-between">
                    <div className="space-y-0.5">
                      <Label className="text-brown-700">Notifications par email</Label>
                      <p className="text-sm text-brown-600">Recevoir les notifications de nouvelles notes</p>
                    </div>
                    <Switch
                      checked={preferences.emailNotifications}
                      onCheckedChange={() => handlePreferenceChange("emailNotifications")}
                    />
                  </div>
                  <div className="flex items-center justify-between">
                    <div className="space-y-0.5">
                      <Label className="text-brown-700">Rappels d'examens</Label>
                      <p className="text-sm text-brown-600">Recevoir des rappels avant les examens</p>
                    </div>
                    <Switch
                      checked={preferences.examReminders}
                      onCheckedChange={() => handlePreferenceChange("examReminders")}
                    />
                  </div>
                  <div className="flex items-center justify-between">
                    <div className="space-y-0.5">
                      <Label className="text-brown-700">Mode sombre</Label>
                      <p className="text-sm text-brown-600">Utiliser le thème sombre</p>
                    </div>
                    <Switch checked={preferences.darkMode} onCheckedChange={() => handlePreferenceChange("darkMode")} />
                  </div>
                </CardContent>
              </Card>

              {/* Actions du compte */}
              <Card className="lg:col-span-2 border-beige-200 bg-white">
                <CardHeader>
                  <CardTitle className="text-brown-800">Actions du compte</CardTitle>
                  <CardDescription className="text-brown-600">Gérer votre compte Sama-Note</CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <Button
                      variant="outline"
                      onClick={handleDownloadTranscript}
                      className="flex items-center space-x-2 border-beige-300 hover:bg-beige-100 text-brown-700 bg-transparent"
                    >
                      <Download className="h-4 w-4" />
                      <span>Télécharger relevé</span>
                    </Button>
                    <Button
                      variant="outline"
                      onClick={handleContactSupport}
                      className="flex items-center space-x-2 border-beige-300 hover:bg-beige-100 text-brown-700 bg-transparent"
                    >
                      <Mail className="h-4 w-4" />
                      <span>Contacter support</span>
                    </Button>
                    <Button
                      variant="outline"
                      onClick={handleConnectionHistory}
                      className="flex items-center space-x-2 border-beige-300 hover:bg-beige-100 text-brown-700 bg-transparent"
                    >
                      <Calendar className="h-4 w-4" />
                      <span>Historique connexions</span>
                    </Button>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>
        </Tabs>
      </div>

      {/* Dialog Changement de mot de passe */}
      <Dialog open={isPasswordDialogOpen} onOpenChange={setIsPasswordDialogOpen}>
        <DialogContent className="bg-white border-beige-200">
          <DialogHeader>
            <DialogTitle className="text-brown-800">Modifier le mot de passe</DialogTitle>
            <DialogDescription className="text-brown-600">Entrez votre nouveau mot de passe</DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            <div>
              <Label htmlFor="newPassword" className="text-brown-700">
                Nouveau mot de passe
              </Label>
              <Input
                id="newPassword"
                type="password"
                value={passwordForm.newPassword}
                onChange={(e) => setPasswordForm((prev) => ({ ...prev, newPassword: e.target.value }))}
                placeholder="Entrez votre nouveau mot de passe"
                className="border-beige-300 focus:border-brown-500"
              />
            </div>
            <div>
              <Label htmlFor="confirmPassword" className="text-brown-700">
                Confirmer le mot de passe
              </Label>
              <Input
                id="confirmPassword"
                type="password"
                value={passwordForm.confirmPassword}
                onChange={(e) => setPasswordForm((prev) => ({ ...prev, confirmPassword: e.target.value }))}
                placeholder="Confirmez votre nouveau mot de passe"
                className="border-beige-300 focus:border-brown-500"
              />
            </div>
          </div>
          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setIsPasswordDialogOpen(false)}
              className="border-beige-300 hover:bg-beige-100"
            >
              Annuler
            </Button>
            <Button onClick={handlePasswordChange} className="bg-brown-700 hover:bg-brown-800 text-white">
              Modifier le mot de passe
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  )
}
