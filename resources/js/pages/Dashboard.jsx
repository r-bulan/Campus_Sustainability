import React, { useState } from 'react';
import { base44 } from '@/api/base44Client';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Button } from '@/components/ui/button';
import { Plus, TrendingUp, FolderTree, Award } from 'lucide-react';
import StatCard from '../components/dashboard/StatCard';
import InitiativeTable from '../components/dashboard/InitiativeTable';
import InitiativeModal from '../components/dashboard/InitiativeModal';
import { motion } from 'framer-motion';

export default function Dashboard() {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingInitiative, setEditingInitiative] = useState(null);
  const queryClient = useQueryClient();

  const { data: initiatives = [], isLoading: initiativesLoading } = useQuery({
    queryKey: ['initiatives'],
    queryFn: () => base44.entities.Initiative.list('-created_date'),
  });

  const { data: categories = [], isLoading: categoriesLoading } = useQuery({
    queryKey: ['categories'],
    queryFn: () => base44.entities.Category.list(),
  });

  const createMutation = useMutation({
    mutationFn: (data) => base44.entities.Initiative.create(data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['initiatives'] });
      setIsModalOpen(false);
      setEditingInitiative(null);
    },
  });

  const updateMutation = useMutation({
    mutationFn: ({ id, data }) => base44.entities.Initiative.update(id, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['initiatives'] });
      setIsModalOpen(false);
      setEditingInitiative(null);
    },
  });

  const deleteMutation = useMutation({
    mutationFn: (id) => base44.entities.Initiative.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['initiatives'] });
    },
  });

  const handleSubmit = (formData) => {
    if (editingInitiative) {
      updateMutation.mutate({ id: editingInitiative.id, data: formData });
    } else {
      createMutation.mutate(formData);
    }
  };

  const handleEdit = (initiative) => {
    setEditingInitiative(initiative);
    setIsModalOpen(true);
  };

  const handleDelete = (initiative) => {
    if (confirm('Are you sure you want to delete this initiative?')) {
      deleteMutation.mutate(initiative.id);
    }
  };

  const handleAddNew = () => {
    setEditingInitiative(null);
    setIsModalOpen(true);
  };

  const totalImpactScore = initiatives.reduce((sum, init) => sum + (init.impact_score || 0), 0);

  const stats = [
    {
      title: 'Total Initiatives',
      value: initiatives.length,
      icon: TrendingUp,
      color: 'bg-gradient-to-br from-emerald-500 to-green-600'
    },
    {
      title: 'Total Categories',
      value: categories.length,
      icon: FolderTree,
      color: 'bg-gradient-to-br from-blue-500 to-indigo-600'
    },
    {
      title: 'Total Impact Score',
      value: totalImpactScore,
      icon: Award,
      color: 'bg-gradient-to-br from-amber-500 to-orange-600'
    }
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white border-b border-gray-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
          <motion.div
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
          >
            <h1 className="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Initiatives Dashboard</h1>
            <p className="text-sm sm:text-base text-gray-500">Track and manage your campus sustainability initiatives</p>
          </motion.div>
        </div>
      </div>

      {/* Main Content */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        {/* Stats Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
          {stats.map((stat, index) => (
            <StatCard key={stat.title} {...stat} index={index} />
          ))}
        </div>

        {/* Table Section */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: 0.3 }}
        >
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
              <h2 className="text-xl sm:text-2xl font-bold text-gray-900">All Initiatives</h2>
              <p className="text-sm text-gray-500 mt-1">
                {initiatives.length} {initiatives.length === 1 ? 'initiative' : 'initiatives'} tracked
              </p>
            </div>
            <Button
              onClick={handleAddNew}
              className="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white shadow-lg shadow-emerald-500/30 w-full sm:w-auto"
            >
              <Plus className="w-5 h-5 mr-2" />
              Add Initiative
            </Button>
          </div>

          {initiativesLoading ? (
            <div className="bg-white rounded-2xl p-12 text-center border border-gray-100">
              <div className="animate-spin w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full mx-auto"></div>
              <p className="text-gray-500 mt-4">Loading initiatives...</p>
            </div>
          ) : (
            <InitiativeTable
              initiatives={initiatives}
              categories={categories}
              onEdit={handleEdit}
              onDelete={handleDelete}
            />
          )}
        </motion.div>
      </div>

      {/* Modal */}
      <InitiativeModal
        isOpen={isModalOpen}
        onClose={() => {
          setIsModalOpen(false);
          setEditingInitiative(null);
        }}
        onSubmit={handleSubmit}
        initiative={editingInitiative}
        categories={categories}
      />
    </div>
  );
}
